<?php
// shortcode
function go_to_webinar_registration( $atts ) {
    global $time_zone_list;
    global $gotowebinar_is_pro;
      
    $options = get_option('gotowebinar_settings');
    
    //if there's a webinar key in the query string set the key and hide to the query string
    if(isset($_GET['webinarKey'])) {
    $a = shortcode_atts( array(
            'key' => $_GET['webinarKey'],
            'hide' => $_GET['hide'],
            'mailchimp' => $options['gotowebinar_mailchimp_default_list'],
            'constantcontact' => $options['gotowebinar_constantcontact_default_list'],
        ), $atts );
        
    //else get the values from the shortcode 
    } else {  
       $a = shortcode_atts( array(
            'key' => '',
            'hide' => '',
            'mailchimp' => $options['gotowebinar_mailchimp_default_list'],
            'constantcontact' => $options['gotowebinar_constantcontact_default_list'],
        ), $atts ); 
    //if the webinar key is upcoming get the webinar key of the most upcoming webinar    
    if($a['key'] == "upcoming") { 
        
    //call upcoming webinars function and store responses as variables    
    $transientName = 'gtw_upc_'.current_time( 'd', $gmt = 0 );
    list($jsondata,$json_response) = wp_gotowebinar_upcoming_webinars($transientName, 86400);    
    
    $i = 0;
    $max_webinars = 1;
        
    foreach ($jsondata as $data) {
    $a['key'] = $data['webinarKey'];
    if(++$i == $max_webinars) break;    
    }    
    } //end if     
        
    } //end else
    
    //establishing of transients and ajax request start here
    $transientName = 'gtw_upc_'.current_time( 'd', $gmt = 0 ).'_'.$a['key']; 
    $getTransient = get_transient($transientName);
    if ($getTransient != false && $options['gotowebinar_disable_cache'] != 1){
        $jsondata = $getTransient;
        $json_response = 200;
    } else {
        $json_feed = wp_remote_get( 'https://api.getgo.com/G2W/rest/organizers/'.$options['gotowebinar_organizer_key'].'/webinars/'.$a['key'], array(
        'headers' => array(
        'Authorization' => $options['gotowebinar_authorization'],
	),));
   
    $jsondata = json_decode(preg_replace('/("\w+"):(\d+(\.\d+)?)/', '\\1:"\\2"', $json_feed['body']), true);
    $json_response = wp_remote_retrieve_response_code($json_feed);    
    
    if($json_response == 200){     
    set_transient($transientName,$jsondata, 86400);  
    }       
    }
        
        
    //check if there's a webinar key to display registration form otherwise show a message    
    if(strlen($a['key'])>0){ 
    //if the response is successful display registration form     
    if($json_response == 200){     
        

    //start of display of webinar details
    $html = '<div class="webinar-registration-header">'; 
    //title
    $html .= '<h3 style="margin-bottom: 10px;">'.str_replace($a['hide'],"",$jsondata['subject']).'</h3>'; 
    foreach($jsondata['times'] as $mytimes) {   
    $html .= '<div id="date-time-duration-details">';   
    
        
    //date  
    $date = new DateTime($mytimes['startTime']);  
    $date->setTimeZone(new DateTimeZone($jsondata['timeZone']));     
    $html .= '<span';
    if($options['gotowebinar_disable_tooltip'] != 1){
    $html .= ' class="masterTooltip" title="'.   date_i18n( 'l', strtotime($mytimes['startTime']) )    .'"';    
    }  
        
    $html .= '><i class="fa fa-calendar" aria-hidden="true"></i><span class="webinar-date">'.$date->format($options['gotowebinar_date_format']).'</span><span style="display:none;" id="webinar-date-format">'.$options['gotowebinar_date_format'].'</span></span>';
    $formdate = $date->format($options['gotowebinar_date_format']);
        
        
    //time
    $startingtime = new DateTime($mytimes['startTime']);
    $startingtime->setTimeZone(new DateTimeZone($jsondata['timeZone']));    
    $html .= '<span ';
    if($options['gotowebinar_disable_tooltip'] != 1){
     $html .= 'class="masterTooltip" title="GMT '.$time_zone_list[$jsondata['timeZone']] .'"';         
    }
    if(isset($options['gotowebinar_time_format'])){
        $options['gotowebinar_time_format'] = $options['gotowebinar_time_format'];    
    } else {
        $options['gotowebinar_time_format'] = "g:ia T";    
    }    

    $html .= '><i class="fa fa-clock-o" aria-hidden="true"></i><span class="webinar-time">'.$startingtime->format($options['gotowebinar_time_format']).'</span><span id="webinars-moment" style="display:none;">'.$mytimes['startTime'].'</span><span style="display:none;" id="webinar-time-format">'.$options['gotowebinar_time_format'].'</span></span>';
    $formtime = $startingtime->format($options['gotowebinar_time_format']);  
        
        
    //duration
    $html .= '<span><i class="fa fa-hourglass-half" aria-hidden="true"></i>';
    $time_diff = strtotime($mytimes['endTime']) - strtotime($mytimes['startTime']);
    if($time_diff/60/60 < 1) {
    $html .= $time_diff/60 . ' '.__( 'minutes', 'wp-gotowebinar' ).'</br>';  
    } else if ($time_diff/60/60 == 1) {
         $html .= $time_diff/60/60 . ' '.__( 'hour', 'wp-gotowebinar' ).'</br>';
    }
    else {
    $html .= $time_diff/60/60 . ' '.__( 'hours', 'wp-gotowebinar' ).'</br>';
    }   
    $html .= '</span>';    
    $html .= '</div>';     
    }
    //if timezone conversion is enabled show the conversion link
    if($options['gotowebinar_enable_timezone_conversion'] == 1){
    $html .= '<p><a class="timezone-convert-link-registration">'.__( 'Convert to my timezone', 'wp-gotowebinar' ).'</a></p>';
    $html .= '<span id="timezone_error_message" style="display:none;">';
         if(isset($options['gotowebinar_timezone_error_message']) && strlen($options['gotowebinar_timezone_error_message'])>0){
             $html .= $options['gotowebinar_timezone_error_message'];     
         } else {   
             $html .= 'Sorry, your location could not be determined.';
         }
         $html .= '</span>';     
    }
    //description
    $html .= '<em>'.nl2br($jsondata['description']).'</em></br>';
    $html .= '</div>'; 
    //establishing of transients and ajax request for form fields
    $transientNameForm = 'gtw_for_'.current_time( 'd', $gmt = 0 ).'_'.$a['key']; 
    $getTransientForm = get_transient($transientNameForm);
    if ($getTransientForm != false && $options['gotowebinar_disable_cache'] != 1){
        $jsondataform = $getTransientForm; 
    } else {
        $json_feed_form = wp_remote_get( 'https://api.getgo.com/G2W/rest/organizers/'.$options['gotowebinar_organizer_key'].'/webinars/'.$a['key'].'/registrants/fields', array(
        'headers' => array(
        'Authorization' => $options['gotowebinar_authorization'],
	),));
    
    $jsondataform = json_decode(preg_replace('/("\w+"):(\d+(\.\d+)?)/', '\\1:"\\2"', $json_feed_form['body']), true);
            
        
    set_transient($transientNameForm,$jsondataform, 86400);  
    }
    
    

    
    //start form table
    $html .= '<form id="webinar-registration-form" class="webinar-registration-form">';
    $html .= '<table class="webinar-registration">';
    
    //start fields inputs
    foreach($jsondataform['fields'] as $field){
$html .= '<tr><td><label';
  if($field['required'] == true) {
          $html .= ' class="gotowebinar-required"';     
  }  
        

$html .= ' for="'.$field['field'].'">';
    

    if($field['field'] == "firstName" && strlen($options['gotowebinar_translate_firstName'])>0) {
        $html .= $options['gotowebinar_translate_firstName'];
    } elseif ($field['field'] == "lastName" && strlen($options['gotowebinar_translate_lastName'])>0) { 
       $html .= $options['gotowebinar_translate_lastName']; 
    } elseif ($field['field'] == "email" && strlen($options['gotowebinar_translate_email'])>0) { 
       $html .= $options['gotowebinar_translate_email']; 
    } elseif ($field['field'] == "address" && strlen($options['gotowebinar_translate_address'])>0) { 
       $html .= $options['gotowebinar_translate_address']; 
    } elseif ($field['field'] == "city" && strlen($options['gotowebinar_translate_city'])>0) { 
       $html .= $options['gotowebinar_translate_city']; 
    } elseif ($field['field'] == "state" && strlen($options['gotowebinar_translate_state'])>0) { 
       $html .= $options['gotowebinar_translate_state']; 
    } elseif ($field['field'] == "zipCode" && strlen($options['gotowebinar_translate_zipCode'])>0) { 
       $html .= $options['gotowebinar_translate_zipCode']; 
    } elseif ($field['field'] == "country" && strlen($options['gotowebinar_translate_country'])>0) { 
       $html .= $options['gotowebinar_translate_country']; 
    } elseif ($field['field'] == "phone" && strlen($options['gotowebinar_translate_phone'])>0) { 
       $html .= $options['gotowebinar_translate_phone']; 
    } elseif ($field['field'] == "organization" && strlen($options['gotowebinar_translate_organization'])>0) { 
       $html .= $options['gotowebinar_translate_organization']; 
    } elseif ($field['field'] == "jobTitle" && strlen($options['gotowebinar_translate_jobTitle'])>0) { 
       $html .= $options['gotowebinar_translate_jobTitle']; 
    } elseif ($field['field'] == "questionsAndComments" && strlen($options['gotowebinar_translate_questionsAndComments'])>0) { 
       $html .= $options['gotowebinar_translate_questionsAndComments']; 
    } elseif ($field['field'] == "industry" && strlen($options['gotowebinar_translate_industry'])>0) { 
       $html .= $options['gotowebinar_translate_industry']; 
    } elseif ($field['field'] == "numberOfEmployees" && strlen($options['gotowebinar_translate_numberOfEmployees'])>0) { 
       $html .= $options['gotowebinar_translate_numberOfEmployees']; 
    } elseif ($field['field'] == "purchasingTimeFrame" && strlen($options['gotowebinar_translate_purchasingTimeFrame'])>0) { 
       $html .= $options['gotowebinar_translate_purchasingTimeFrame']; 
    } elseif ($field['field'] == "purchasingRole" && strlen($options['gotowebinar_translate_purchasingRole'])>0) { 
       $html .= $options['gotowebinar_translate_purchasingRole'];
    }
    else {
        $html .= ucwords(preg_replace('/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]|[0-9]{1,}/', ' $0', $field['field']));   
    }
        
    
    $html .= '</label></td></tr>';
        
$html .= '<tr><td>';
if(isset($field['answers'])) {
$html .= '<select class="gotowebinar-field" name="'.$field['field'].'" id="'.$field['field'].'" ';
 if ($field['maxSize']){
     $html .= 'maxlength="'.$field['maxSize'].'" ';   
    }
    if ($field['required'] == true){
     $html .= 'required ';   
    }    
$html .= '>';
$html .= '<option value="">--Select--</option>';    
foreach($field['answers'] as $answer){    
$html .= '<option value="'.$answer.'">'.$answer.'</option>';
} //end select options foreach
$html .= '</select>';    
} else { //end select inputs
$html .= '<input class="gotowebinar-field" id="'.$field['field'].'" name="'.$field['field'].'" type="text" ';
    if ($field['maxSize']){
     $html .= 'maxlength="'.$field['maxSize'].'" ';   
    }
    if ($field['required'] == true){
     $html .= 'required ';   
    }
$html .= '>';   
} //end normal text field input      
$html .= '</td></tr>';    
} //end for each fields
    
    
//start questions inputs    
foreach($jsondataform['questions'] as $question){ 
$html .= '<tr><td><label';
  if($question['required'] == true) {
          $html .= ' class="gotowebinar-required"';     
  }             
$html .= ' for="'.$question['questionKey'].'">'.$question['question'].'</label></td></tr>';    
    $html .= '<tr><td>';   
    if($question['type'] == "shortAnswer"){
    $html .= '<input class="gotowebinar-question" id="'.$question['questionKey'].'" name="'.$question['questionKey'].'" type="text" ';
    if ($question['maxSize']){
     $html .= 'maxlength="'.$question['maxSize'].'" ';   
    }
    if ($question['required'] == true){
     $html .= 'required ';   
    }
$html .= '>';  
    } else { //end input
        $html .= '<select class="gotowebinar-question gotowebinar-select" name="'.$question['questionKey'].'" id="'.$question['questionKey'].'" ';
 if ($question['maxSize']){
     $html .= 'maxlength="'.$question['maxSize'].'" ';   
    }
    if ($question['required'] == true){
     $html .= 'required ';   
    }    
$html .= '>';
$html .= '<option value="">--Select--</option>';
foreach($question['answers'] as $answer){    
$html .= '<option value="'.$answer['answerKey'].'">'.$answer['answer'].'</option>';
} //end select options foreach
$html .= '</select>';
    } //end select
    $html .= '</td></tr>';  
} //end for each questions
    

    //check if user is logged in
    if ( is_user_logged_in() ) {
        //get current user
        $current_user = wp_get_current_user();
        //current user email
        $html .= '<tr style="display:none;">';
        $html .= '<td><input name="gotowebinar_current_user_email" id="gotowebinar_current_user_email" type="text" value="'.$current_user->user_email.'"></td></tr>';     
        //current user first name    
        $html .= '<tr style="display:none;">';
        $html .= '<td><input name="gotowebinar_current_user_first_name" id="gotowebinar_current_user_first_name" type="text" value="'.$current_user->user_firstname.'"></td></tr>';     
        //current user last name    
        $html .= '<tr style="display:none;">';
        $html .= '<td><input name="gotowebinar_current_user_last_name" id="gotowebinar_current_user_last_name" type="text" value="'.$current_user->user_lastname.'"></td></tr>';     
    }  
        

    //start hidden fields
    //source
    $html .= '<tr style="display:none;">';
    $html .= '<td><input name="gotowebinar_registration_source" id="gotowebinar_registration_source" type="text" value="Main Website"></td></tr>'; 
    //webinarkey
    $html .= '<tr style="display:none;">';
    $html .= '<td><input name="gotowebinar_registration_webinar_key" id="gotowebinar_registration_webinar_key" type="text" value="'.$a['key'].'"></td></tr>';
    //webinartitle
    $html .= '<tr style="display:none;">';
    $html .= '<td><input name="gotowebinar_registration_webinar_title" id="gotowebinar_registration_webinar_title" type="text" value="'.str_replace($a['hide'],"",$jsondata['subject']).'"></td></tr>';
    //webinartime
    $html .= '<tr style="display:none;">';
    $html .= '<td><input name="gotowebinar_registration_webinar_time" id="gotowebinar_registration_webinar_time" type="text" value="'.$formtime.'"></td></tr>';
    //webinardate
    $html .= '<tr style="display:none;">';
    $html .= '<td><input name="gotowebinar_registration_webinar_date" id="gotowebinar_registration_webinar_date" type="text" value="'.$formdate.'"></td></tr>';  
    //webinarregistrationurl
    $html .= '<tr style="display:none;">';
    $html .= '<td><input name="gotowebinar_registration_url" id="gotowebinar_registration_url" type="text" value="'.$jsondata['registrationUrl'].'"></td></tr>';
    //mailchimpdefaultlist
    $html .= '<tr style="display:none;"><td></td>';
    $html .= '<td><input name="gotowebinar_mailchimp_default_list" id="gotowebinar_mailchimp_default_list" type="text" value="'.$a['mailchimp'].'"></td></tr>';  
    //constantcontactdefaultlist
    $html .= '<tr style="display:none;"><td></td>';
    $html .= '<td><input name="gotowebinar_constantcontact_default_list" id="gotowebinar_constantcontact_default_list" type="text" value="'.$a['constantcontact'].'"></td></tr>';  
    //MailChimp SubscribeIf
    $html .= '<tr style="display:none;">';
    $html .= '<td><input name="gotowebinar_mailchimp_subscribe_if" id="gotowebinar_mailchimp_subscribe_if" type="text" value="'.$options['gotowebinar_mailchimp_subscribe_if'].'"></td></tr>';
    //successMessage
    $html .= '<tr style="display:none;">';
    $html .= '<td><input name="gotowebinar_translate_successMessage" id="gotowebinar_translate_successMessage" type="text" value="'.$options['gotowebinar_translate_successMessage'].'"></td></tr>';
    //alreadyRegisteredMessage
    $html .= '<tr style="display:none;">';
    $html .= '<td><input name="gotowebinar_translate_alreadyRegisteredMessage" id="gotowebinar_translate_alreadyRegisteredMessage" type="text" value="'.$options['gotowebinar_translate_alreadyRegisteredMessage'].'"></td></tr>';
    //errorMessage
    $html .= '<tr style="display:none;">';
    $html .= '<td><input name="gotowebinar_translate_errorMessage" id="gotowebinar_translate_errorMessage" type="text" value="'.$options['gotowebinar_translate_errorMessage'].'"></td></tr>';
    //customThankYouPage
    $html .= '<tr style="display:none;">';
    $html .= '<td><input name="gotowebinar_custom_thankyou_page" id="gotowebinar_custom_thankyou_page" type="text" value="'.get_permalink($options['gotowebinar_custom_thankyou_page']).'"></td></tr>';
    
    //shows opt in condition
    if($options['gotowebinar_emailservice_opt_in'] != 1 && $gotowebinar_is_pro == "YES"){
    $html .= '<tr><td><label for="gotowebinar_opt_in">Sign me up to the mailing list</label></td>';
    $html .= '<td><input name="gotowebinar_opt_in" id="gotowebinar_opt_in" type="checkbox" checked></td></tr>'; 
    } 
    
    //google recaptcha    
    if(isset($options['gotowebinar_recaptcha_site_key']) && strlen($options['gotowebinar_recaptcha_site_key']) > 0){    
    $html .= '<tr><td>';    
    $html .= '<div class="g-recaptcha" data-sitekey="'.$options['gotowebinar_recaptcha_site_key'].'"></div>';
    $html .= '</td></tr>';
    }
    
    //submit button and closing tags
    $html .= '<tr>';
    $html .= '<td><input id="gotowebinar_registration_submit" class="gotowebinar_registration_submit" value="';
    if(strlen($options['gotowebinar_translate_submitButton'])>0) {
        $html .= $options['gotowebinar_translate_submitButton'];
    } 
    else {
        $html .= "Submit";   
    }
    $html .= '" type=submit>';
    $html .= '<i class="fa fa-spinner" aria-hidden="true"></i>';
    $html .= '</td></tr>';
    $html .= '</table>';
    $html .= '</form>';
    return $html; //end form
    
    //this is the error thrown if there's a 400 status    
    } else {
        if(isset($options['gotowebinar_translate_cancelledWebinar']) && strlen($options['gotowebinar_translate_cancelledWebinar'])>0) {
            echo html_entity_decode(stripslashes($options['gotowebinar_translate_cancelledWebinar']));    
        } else {
            echo "The webinar either no longer exists or an error has occured.";    
        }
    }
        
    //this is the error if a person goes to a blank registration page with no webinar id
    } else {
        echo "Thanks for using WP GoToWebinar. The shortcode has been implemented correctly. This page is required if you wish to display GoToWebinar registration forms on your own website. However this page requires a parameter at the end of the URL when accessed so the page knows what registration form to display for a given webinar. So on your upcoming webinars display when you click on a register link it will go to this page and send a parameter to it so that page knows what registration form to display. So if you were expecting a form here don't worry everything is working fine.";
    } 
    
    
}
add_shortcode('gotowebinar-reg', 'go_to_webinar_registration');
add_shortcode('gotowebinar-reg-gen', 'go_to_webinar_registration');
//creates shortcode for any page and also visual composer - the visual composer one is required because otherwise it would share a namespace
?>