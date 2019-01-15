<?php
defined( 'ABSPATH' ) or die( 'No direct access!' );

include_once(__DIR__ . '/controller.php');

class Hpj_CMLabs_User_Controller extends Hpj_CMLabs_Controller {

    public function profileAction() {
        global $current_user;
        
        $url = get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_ACCOUNT));
        $formDatas = Hpj_CMLabs_Form::getFormData($url);
        if (empty($formDatas) && is_user_logged_in()) {
            $user_data = get_userdata( $current_user->ID );
	    if ( !empty( $user_data ) ) {
		$formDatas['email'] = $user_data->user_email;
	    } else {
		$formDatas['email'] = '';
	    }
	    
            $formDatas['title'] = get_user_meta( $current_user->ID, 'user_title', true );
            $formDatas['first_name'] = get_user_meta( $current_user->ID, 'first_name', true );
            $formDatas['last_name'] = get_user_meta( $current_user->ID, 'last_name', true );
            $formDatas['city'] = get_user_meta( $current_user->ID, 'user_city', true );
            $formDatas['state'] = get_user_meta( $current_user->ID, 'user_state', true );
            $formDatas['country'] = get_user_meta( $current_user->ID, 'user_country', true );
            $formDatas['company'] = get_user_meta( $current_user->ID, 'user_company', true );
            $formDatas['website'] = get_user_meta( $current_user->ID, 'user_website', true );
            $formDatas['phone'] = get_user_meta( $current_user->ID, 'user_phone', true );
            $formDatas['twitter'] = get_user_meta( $current_user->ID, 'user_twitter_profile', true );
            $formDatas['linkedin'] = get_user_meta( $current_user->ID, 'user_linkedin_profile', true );
            $formDatas['primary_use'] = get_user_meta( $current_user->ID, 'user_primary_use', true );
            $formDatas['type_equipment_simulated'] = get_user_meta( $current_user->ID, 'user_type_equipment_simulated', true );
	    $formDatas['other_sim_tools'] = get_user_meta( $current_user->ID, 'other_sim_tools', true );
	    $formDatas['no_sim_tools'] = get_user_meta( $current_user->ID, 'no_sim_tools', true );
	    $formDatas['proj_description'] = get_user_meta( $current_user->ID, 'proj_description', true );
	    $formDatas['knowledge_background'] = get_user_meta( $current_user->ID, 'knowledge_background', true );
	    $formDatas['company_industry'] = get_user_meta( $current_user->ID, 'company_industry', true );
	    $formDatas['other_company_industry'] = get_user_meta( $current_user->ID, 'other_company_industry', true );
	    $formDatas['other_knowledge_background'] = get_user_meta( $current_user->ID, 'other_knowledge_background', true );
	    $formDatas['vortex_source'] = get_user_meta( $current_user->ID, 'vortex_source', true );
	    $formDatas['other_vortex_source'] = get_user_meta( $current_user->ID, 'other_vortex_source', true );
	    $formDatas['client_industry'] = get_user_meta( $current_user->ID, 'client_industry', true );
	    $formDatas['other_client_industry'] = get_user_meta( $current_user->ID, 'other_client_industry', true );
	    $formDatas['company_size'] = get_user_meta( $current_user->ID, 'company_size', true );
	    $formDatas['use_type'] = get_user_meta( $current_user->ID, 'use_type', true );
	    $formDatas['other_use_type'] = get_user_meta( $current_user->ID, 'other_use_type', true );
	    
	    
	    global $sim_tools;
	    foreach ( $sim_tools as $sim_tool ) {
		$formDatas[sanitize_title( $sim_tool )] = get_user_meta( $current_user->ID, sanitize_title( $sim_tool ), true );
	    }	  
        }                         
        $datas = array(
            'current_user' => $current_user,
            'form_datas' => $formDatas,
        );
        return array(
            'view' => 'public/user/profile.php',
            'data' => $datas
        );
    }

    public function profileSaveAction() {
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'hpj-cmlabs-update-profile' ) {
	    
            global $current_user;
            if (is_user_logged_in()) {
                $errors = array();
                //$url = get_site_url(null, HPJ_CMLABS_URL_ACCOUNT);
                $url = get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_ACCOUNT_PROFILE));
                // Put post data in session
                Hpj_CMLabs_Form::addFormData($url, $_POST);

                /* Update user password. */
                if ( !empty($_POST['newpass1']) || !empty($_POST['newpass2'])) {
                    /*
                    if ( $_POST['pass1'] == $_POST['pass2'] )
                        wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => esc_attr( $_POST['pass1'] ) ) );
                    else
                        $errors[] = __('The passwords you entered do not match.  Your password was not updated.', HPJ_CMLABS_I18N_DOMAIN);
                    */
                    if (strlen($_POST['newpass1']) < 6) {
                        $errors[] = __('Passwords require a minimum of 6 characters.', HPJ_CMLABS_I18N_DOMAIN);        
                    } else if ( $_POST['newpass1'] != $_POST['newpass2'] ) {
                        $errors[] = __('The passwords you entered do not match.  Your password was not updated.', HPJ_CMLABS_I18N_DOMAIN);
                    }
                }
                /*if ( !empty($_POST['pass1'] )) {
                        wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => esc_attr( $_POST['pass1'] ) ) );
                }*/

                /* Update user information. */
                if ( !empty( $_POST['email'] ) ){
                    if (!is_email(esc_attr( $_POST['email'] )))
                        $errors[] = __('The Email you entered is not valid.  please try again.', HPJ_CMLABS_I18N_DOMAIN);
                    else{
                        $emailExist = email_exists(esc_attr( $_POST['email'] ));
                        if (!($emailExist === false || $emailExist == $current_user->id)) {
                            $errors[] = __('This email is already used by another user.  try a different one.', HPJ_CMLABS_I18N_DOMAIN);
                        }
                    }
                } else {
                    $errors[] = __('The Email is required.', HPJ_CMLABS_I18N_DOMAIN);    
                }
		
                $title = ( !empty( $_POST['title'] ) ) ? $_POST['title'] : '';
                $firstName = ( !empty( $_POST['first_name'] ) ) ? $_POST['first_name'] : '';
                $lastName = ( !empty( $_POST['last_name'] ) ) ? $_POST['last_name'] : '';
                $city = ( !empty( $_POST['city'] ) ) ? $_POST['city'] : '';
                $state = ( !empty( $_POST['state'] ) ) ? $_POST['state'] : '';
                $country = ( !empty( $_POST['country'] ) ) ? $_POST['country'] : '';
                $company = ( !empty( $_POST['company'] ) ) ? $_POST['company'] : '';
                $phone = ( !empty( $_POST['phone'] ) ) ? $_POST['phone'] : '';
                $website = ( !empty( $_POST['website'] ) ) ? $_POST['website'] : '';
                $twitter = ( !empty( $_POST['twitter'] ) ) ? $_POST['twitter'] : '';
                $linkedin = ( !empty( $_POST['linkedin'] ) ) ? $_POST['linkedin'] : '';
                $primaryUse = ( !empty( $_POST['primary_use'] ) ) ? $_POST['primary_use'] : '';
                $typeEquipmentSimulated = ( !empty( $_POST['type_equipment_simulated'] ) ) ? $_POST['type_equipment_simulated'] : '';
		$otherSimTools = ( !empty( $_POST['other_sim_tools'] ) ) ? $_POST['other_sim_tools'] : '';
		$proj_description = ( !empty( $_POST['proj_description'] ) ) ? $_POST['proj_description'] : '';
		$knowledge_background = ( !empty( $_POST['knowledge_background'] ) ) ? $_POST['knowledge_background'] : '';
		$other_knowledge_background = ( !empty( $_POST['other_knowledge_background'] ) ) ? $_POST['other_knowledge_background'] : '';
		$company_industry = ( !empty( $_POST['company_industry'] ) ) ? $_POST['company_industry'] : '';
		$other_company_industry = ( !empty( $_POST['other_company_industry'] ) ) ? $_POST['other_company_industry'] : '';
		$vortex_source = ( !empty( $_POST['vortex_source'] ) ) ? $_POST['vortex_source'] : '';
		$other_vortex_source = ( !empty( $_POST['other_vortex_source'] ) ) ? $_POST['other_vortex_source'] : '';
		$client_industry = ( !empty( $_POST['client_industry'] ) ) ? $_POST['client_industry'] : '';
		$other_client_industry = ( !empty( $_POST['other_client_industry'] ) ) ? $_POST['other_client_industry'] : '';
		$company_size = ( !empty( $_POST['company_size'] ) ) ? $_POST['company_size'] : '';
		$use_type = ( !empty( $_POST['use_type'] ) ) ? $_POST['use_type'] : '';
		$other_use_type = ( !empty( $_POST['other_use_type'] ) ) ? $_POST['other_use_type'] : '';
		
		
		if ( isset( $_POST['no_sim_tools'] ) ) {
		    $noSimTools = true;
		} else {
		    $noSimTools = false;
		}
		
		global $sim_tools;
		$sim_tool_entry = array();
		foreach ( $sim_tools as $sim_tool ) {
		    if ( isset( $_POST[sanitize_title( $sim_tool )] ) ) {
			$sim_tool_entry[sanitize_title( $sim_tool )] = true;
		    } else {
			$sim_tool_entry[sanitize_title( $sim_tool )] = false;
		    }
		}
		
                /*I am not Author of this Code- i dont know why but it worked for me after changing below line to if ( count($error) == 0 ){ */
                if ( count($errors) == 0 ) {
		    
		    $missing_items = 0;
                    if ( !empty($_POST['newpass1'] )) {
                        wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => esc_attr( $_POST['newpass1'] )));
                    }
                    if ( !empty($_POST['email'] )) {
                        wp_update_user( array ('ID' => $current_user->ID, 'user_email' => esc_attr( $_POST['email'] )));
                    }

                    update_user_meta( $current_user->ID, 'user_title', esc_attr( $title ) );
                    update_user_meta( $current_user->ID, 'user_company', esc_attr( $company ) );
                    update_user_meta( $current_user->ID, 'first_name', esc_attr($firstName ) );
                    update_user_meta( $current_user->ID, 'last_name', esc_attr( $lastName ) );
                    update_user_meta( $current_user->ID, 'user_city', esc_attr( $city ) );
                    update_user_meta( $current_user->ID, 'user_state', esc_attr( $state ) );
                    update_user_meta( $current_user->ID, 'user_country', esc_attr( $country ) );
                    update_user_meta( $current_user->ID, 'user_phone', esc_attr( $phone ) );
                    update_user_meta( $current_user->ID, 'user_website', esc_attr( $website ) );
                    update_user_meta( $current_user->ID, 'user_twitter_profile', esc_attr( $twitter ) );
                    update_user_meta( $current_user->ID, 'user_linkedin_profile', esc_attr( $linkedin ) );
                    update_user_meta( $current_user->ID, 'user_primary_use', esc_attr( $primaryUse ) );
                    update_user_meta( $current_user->ID, 'user_type_equipment_simulated', esc_attr( $typeEquipmentSimulated ) );
		    update_user_meta( $current_user->ID, 'other_sim_tools', esc_attr( $otherSimTools ) );
		    update_user_meta( $current_user->ID, 'no_sim_tools', esc_attr( $noSimTools ) );
		    update_user_meta( $current_user->ID, 'proj_description', esc_attr( $proj_description ) );
		    update_user_meta( $current_user->ID, 'knowledge_background', esc_attr( $knowledge_background ) );
		    update_user_meta( $current_user->ID, 'other_knowledge_background', esc_attr( $other_knowledge_background ) );
		    update_user_meta( $current_user->ID, 'company_industry', esc_attr( $company_industry ) );
		    update_user_meta( $current_user->ID, 'other_company_industry', esc_attr( $other_company_industry ) );
		    update_user_meta( $current_user->ID, 'vortex_source', esc_attr( $vortex_source ) );
		    update_user_meta( $current_user->ID, 'other_vortex_source', esc_attr( $other_vortex_source ) );
		    update_user_meta( $current_user->ID, 'client_industry', esc_attr( $client_industry ) );
		    update_user_meta( $current_user->ID, 'other_client_industry', esc_attr( $other_client_industry ) );
		    update_user_meta( $current_user->ID, 'company_size', esc_attr( $company_size ) );		    
		    update_user_meta( $current_user->ID, 'use_type', esc_attr( $use_type ) );
		    update_user_meta( $current_user->ID, 'other_use_type', esc_attr( $other_use_type ) );
		    
		    foreach ( $sim_tools as $sim_tool ) {
			update_user_meta( $current_user->ID, sanitize_title( $sim_tool ), $sim_tool_entry[sanitize_title( $sim_tool )] );
		    }


		    if ( empty( $title ) || empty( $firstName ) || empty( $lastName ) || empty( $city ) || empty( $state ) || empty( $country ) || empty( $company ) || empty( $knowledge_background ) || ( 'Other (Specify below)' == $knowledge_background && empty( $other_knowledge_background ) ) || empty( $vortex_source ) || ( 'Other (Specify below)' == $vortex_source && empty( $other_vortex_source )) || empty( $company_industry ) || ( 'Other (Specify below)' == $company_industry && empty( $other_company_industry ) ) || ( 'Other (Specify below)' == $client_industry && empty( $other_client_industry ) ) || empty( $company_size ) || empty( $primaryUse ) || empty( $typeEquipmentSimulated ) || empty( $proj_description ) || empty( $use_type ) || ( 'Other (Specify below)' == $use_type && empty( $other_use_type )) ) {
			    Hpj_CMLabs_Notice::addMessage(__('Account has been updated, but some required fields are still missing. Please check all tabs.', HPJ_CMLABS_I18N_DOMAIN));
			    update_user_meta( $current_user->ID, 'profile_complete', false );
			    $missing_items = 1;
		    } else {
			$selected_tool = false;
			foreach ( $sim_tools as $sim_tool ) {
			    if ( $sim_tool_entry[sanitize_title( $sim_tool )] ) {
				$selected_tool = true;
			    }
			}
						
			if ( !$selected_tool && empty( $otherSimTools ) && !$noSimTools ) {
			    Hpj_CMLabs_Notice::addMessage(__('Account has been updated, but some required fields are still missing. Please check all tabs.', HPJ_CMLABS_I18N_DOMAIN));
			    update_user_meta( $current_user->ID, 'profile_complete', false );
			    $missing_items = 2;
			} else {
			    Hpj_CMLabs_Notice::addMessage(__('Account has been updated and all required fields have been completed', HPJ_CMLABS_I18N_DOMAIN));
			    update_user_meta( $current_user->ID, 'profile_complete', true );
			    update_user_meta( $current_user->ID, 'profile_completed_date', date('m/d/Y', time()) );
			}			
		    }
                    // action hook for plugins and extra fields saving
                    // do_action('edit_user_profile_update', $current_user->ID);
                    // Clean user form in session
                    Hpj_CMLabs_Form::cleanFormData($url);
                } else {
                    foreach ($errors as $error) {
                        Hpj_CMLabs_Notice::addError(__($error, HPJ_CMLABS_I18N_DOMAIN));
                    }
                }
                /* Redirect so the page will show updated info.*/
                wp_redirect( add_query_arg( 'missing_items', $missing_items, $url ) );
                exit;
            }
        }
    }

}
