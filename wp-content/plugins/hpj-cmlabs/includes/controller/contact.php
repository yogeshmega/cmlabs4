<?php
defined( 'ABSPATH' ) or die( 'No direct access!' );

include_once(__DIR__ . '/controller.php');           

class Hpj_CMLabs_Contact_Controller extends Hpj_CMLabs_Controller {

    public function contactSaleFormAction() {
        global $current_user;
        //$url = get_site_url(null, HPJ_CMLABS_URL_CONTACT_SALE);
        $url = get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_CONTACT_SALE));
        $email = get_option('hpj_cmlabs_setting_contact_sale_email');
        $formDatas = Hpj_CMLabs_Form::getFormData($url);
        $datas = array(
            'action' => $url,
            'email' => $email,
            'current_user' => $current_user,
            'form_datas' => $formDatas,
            'type' => 'new-license-non-essentials'
        );
        return array(
            'view' => 'public/contact/new_license_non-essentials_form.php',
            'data' => $datas
        );
    }
    
    public function contactSupportFormAction() {
        global $current_user;
        //$url = get_site_url(null, HPJ_CMLABS_URL_CONTACT_SUPPORT);
        $url = get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_CONTACT_SUPPORT));
        $email = get_option('hpj_cmlabs_setting_contact_support_email');
        $formDatas = Hpj_CMLabs_Form::getFormData($url);
        $datas = array(
            'action' => $url,
            'email' => $email,
            'current_user' => $current_user,
            'form_datas' => $formDatas,
            'type' => 'new-license-essentials'
        );
        return array(
            'view' => 'public/contact/new_license_essentials_form.php',
            'data' => $datas
        );
    }
    
    public function contactLicensingFormAction() {
        global $current_user;
        //$url = get_site_url(null, HPJ_CMLABS_URL_CONTACT_LICENSING);
        $url = get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_CONTACT_LICENSING));
        $email = get_option('hpj_cmlabs_setting_contact_licensing_email');
        $formDatas = Hpj_CMLabs_Form::getFormData($url);
        $datas = array(
            'action' => $url,
            'email' => $email,
            'current_user' => $current_user,
            'form_datas' => $formDatas,
            'type' => 'upgrade-legacy-license'
        );
        return array(
            'view' => 'public/contact/upgrade_legacy_license_form.php',
            'data' => $datas
        );
    }
    
    public function contactFormSendAction() {
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'hpj-cmlabs-send-email' ) {
            global $current_user;
            if (is_user_logged_in()) {
                $errors = array();
                $type = $_POST['type'];
                if (!empty($type) && in_array($type, array('new-license-non-essentials', 'new-license-essentials', 'renew'))) {
                    
                    $targetEmail = null;
                    $subject = '';
                    $url = null;
                    switch ($type) {
                        case 'new-license-essentials':
                            $url = get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_CONTACT_SUPPORT));
                            //$url = get_site_url(null, HPJ_CMLABS_URL_CONTACT_SUPPORT);
                            $targetEmail = get_option('hpj_cmlabs_setting_contact_support_email');
                            $subject = HPJ_CMLABS_URL_CONTACT_NEW_LICENSE_ESSENTIALS_SUBJECT;
                        break;
                        case 'new-license-non-essentials':
                            $url = get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_CONTACT_SALE));
                            //$url = get_site_url(null, HPJ_CMLABS_URL_CONTACT_SALE);
                            $targetEmail = get_option('hpj_cmlabs_setting_contact_sale_email');
                            $subject = HPJ_CMLABS_URL_CONTACT_NEW_LICENSE_NON_ESSENTIALS_SUBJECT;
                        break;
                    }
                    if (!empty($url) && !empty($targetEmail)) {     
                        Hpj_CMLabs_Form::addFormData($url, $_POST);
                        if (empty($_POST['first-name']) || trim($_POST['first-name']) == '') {
                            $errors[] = __('Firstname is required', HPJ_CMLABS_I18N_DOMAIN);    
                        }
                        if (empty($_POST['last-name']) || trim($_POST['last-name']) == '') {
                            $errors[] = __('Lastname is required', HPJ_CMLABS_I18N_DOMAIN);    
                        }
                        if (empty($_POST['company']) || trim($_POST['company']) == '') {
                            $errors[] = __('Company is required', HPJ_CMLABS_I18N_DOMAIN);    
                        }
                        if (!empty($_POST['email']) && trim($_POST['email']) != '') {
                            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                                $errors[] = __('Email is not valid', HPJ_CMLABS_I18N_DOMAIN);    
                            }    
                        } else {
                            $errors[] = __('Email is required', HPJ_CMLABS_I18N_DOMAIN);    
                        }
                        if (empty($_POST['phone']) || trim($_POST['phone']) == '') {
                            $errors[] = __('Phone is required', HPJ_CMLABS_I18N_DOMAIN);    
                        }
                        if (empty($_POST['request']) || trim($_POST['request']) == '') {
                            $errors[] = __('Request is required', HPJ_CMLABS_I18N_DOMAIN);    
                        }
                        if (empty($errors)) {
                            $message = '';
                            $message .= 'First Name : ' . $_POST['first-name'] . '<br/>';
                            $message .= 'Last Name : ' . $_POST['last-name'] . '<br/>';
                            $message .= 'Company Name : ' . $_POST['company'] . '<br/>';
                            $message .= 'Email : ' . $_POST['email'] . '<br/>';
                            $message .= 'Phone : ' . $_POST['phone'] . '<br/>';
                            $message .= 'Request : <br/><br/>' . $_POST['request'] . '<br/>';
                            if (wp_mail($targetEmail, $subject, $message, array('Content-Type: text/html; charset=UTF-8'))) {
                                Hpj_CMLabs_Notice::addMessage(__('An Email has been sent succesfully', HPJ_CMLABS_I18N_DOMAIN));    
                            } else {
                                Hpj_CMLabs_Notice::addMessage(__('Email sent has failed', HPJ_CMLABS_I18N_DOMAIN));
                            }
                            Hpj_CMLabs_Form::cleanFormData($url);
                            wp_redirect( get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_LICENSES)) );
                            exit;
                        } else {
                            // There are some errors
                            foreach ($errors as $error) {
                                Hpj_CMLabs_Notice::addError($error);    
                            }
                        }
                    } else {
                        Hpj_CMLabs_Notice::addError('An error occured');    
                    }
                } else {
                    Hpj_CMLabs_Notice::addError('Type is invalid');
                }                             
                wp_redirect( (!empty($url)) ? $url : get_site_url() );
                exit;       
            }    
        }
    }
    
    public function contactLicensingFormSendAction() {
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && ($_POST['action'] == 'hpj-cmlabs-send-upgrade-legacy-license-email' || $_POST['action'] == 'hpj-cmlabs-send-renewal-email') ) {
            global $current_user;
            if (is_user_logged_in()) {
                $errors = array();
                $type = $_POST['type'];
                if (!empty($type) && in_array($type, array('upgrade-legacy-license', 'renewal'))) {
                    $targetEmail = null;
                    $subject = '';
                    $url = null;
                    switch ($type) {
                        case 'upgrade-legacy-license':
                            $url = get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_CONTACT_LICENSING));
                            //$url = get_site_url(null, HPJ_CMLABS_URL_CONTACT_LICENSING);
                            $targetEmail = get_option('hpj_cmlabs_setting_contact_licensing_email');
                            $subject = HPJ_CMLABS_URL_CONTACT_UPGRADE_LEGACY_LICENSE_SUBJECT;
                        break;
                        case 'renewal':
                            $url = get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_CONTACT_RENEW));
                            //$url = get_site_url(null, HPJ_CMLABS_URL_CONTACT_RENEW);
                            $targetEmail = get_option('hpj_cmlabs_setting_contact_sale_email');
                            $subject = HPJ_CMLABS_URL_CONTACT_RENEW_SUBJECT;
                        break;                                                             
                    }                    
        
                    if (!empty($url) && !empty($targetEmail)) {
                        if ($type == 'upgrade-legacy-license') {    
                            Hpj_CMLabs_Form::addFormData($url, $_POST);
                            if (empty($_POST['phone']) || trim($_POST['phone']) == '') {
                                $errors[] = __('Phone is required', HPJ_CMLABS_I18N_DOMAIN);    
                            }
                            if (empty($_POST['license-id']) || trim($_POST['license-id']) == '') {
                                $errors[] = __('License ID is required', HPJ_CMLABS_I18N_DOMAIN);    
                            }
                        } else {
                            if (empty($_POST['prodname']) || trim($_POST['prodname']) == '') {
                                $errors[] = __('Activation key is required', HPJ_CMLABS_I18N_DOMAIN);
                            }
                            if (empty($_POST['akey']) || trim($_POST['akey']) == '') {
                                $errors[] = __('Activation key is required', HPJ_CMLABS_I18N_DOMAIN);    
                            }
                            if (empty($_POST['host_id']) || trim($_POST['host_id']) == '') {
                                $errors[] = __('Host ID is required', HPJ_CMLABS_I18N_DOMAIN);    
                            }
                        }
                        
                        if (empty($errors)) {               
                            $message = '';
                            $message .= 'First Name : ' . get_the_author_meta( 'first_name', $current_user->ID ) . '<br/>';
                            $message .= 'Last Name : ' . get_the_author_meta( 'last_name', $current_user->ID ) . '<br/>';
                            $message .= 'Company Name : ' . get_the_author_meta( 'user_company', $current_user->ID ) . '<br/>';
                            $message .= 'Email : ' . get_the_author_meta( 'user_email', $current_user->ID ) . '<br/>';
                            if ($type == 'upgrade-legacy-license') {
                                $message .= 'Phone : ' . htmlspecialchars($_POST['phone']) . '<br/>';
                                $message .= 'License ID Number(s) : <br/><br/>' . htmlspecialchars($_POST['license-id']) . '<br/>';
                            } else {
                                $message .= 'Product name : ' . htmlspecialchars(trim($_POST['prodname'])) . '<br/>';
                                $message .= 'Activation key : ' . htmlspecialchars(trim($_POST['akey'])) . '<br/>';
                                $message .= 'Host ID : ' . htmlspecialchars(trim($_POST['host_id'])) . '<br/>';    
                            }                      
                            
                            if (wp_mail($targetEmail, $subject, $message, array('Content-Type: text/html; charset=UTF-8'))) {
                                if ($type == 'upgrade-legacy-license') {
                                    Hpj_CMLabs_Notice::addMessage(__('An Email has been sent succesfully', HPJ_CMLABS_I18N_DOMAIN));
                                } else {
                                    Hpj_CMLabs_Notice::addMessage(__('Your license renewal request has been sent. Thanks!', HPJ_CMLABS_I18N_DOMAIN));    
                                }    
                            } else {
                                Hpj_CMLabs_Notice::addMessage(__('Email sent has failed', HPJ_CMLABS_I18N_DOMAIN));
                            }
                            Hpj_CMLabs_Form::cleanFormData($url);
                            wp_redirect( get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_LICENSES)) );
                            exit;
                        } else {
                            // There are some errors
                            foreach ($errors as $error) {
                                Hpj_CMLabs_Notice::addError($error);    
                            }
                        }
                    } else {
                        Hpj_CMLabs_Notice::addError('An error occured');    
                    }
                } else {
                    Hpj_CMLabs_Notice::addError('Type is invalid');
                }
                wp_redirect( (!empty($url)) ? $url : get_site_url() );
                exit;       
            }    
        }
    }
}
