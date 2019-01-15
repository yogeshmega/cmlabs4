<?php
defined( 'ABSPATH' ) or die( 'No direct access!' );

include_once(__DIR__ . '/controller.php');
include_once(__DIR__ . ' /../model/license.php');
include_once(__DIR__ . ' /../model/product.php');
include_once(__DIR__ . ' /../model/fulfillment.php');

class Hpj_CMLabs_License_Controller extends Hpj_CMLabs_Controller {

	public function listAction() {
        global $current_user;
        $view = null;
        $data = array();
        $essentialList = array();
        $runtimeList = array();
        $otherList = array();
        if (is_user_logged_in()) {
            $licenseModel = new Hpj_CMLabs_License_Model();
            $productModel = new Hpj_CMLabs_Product_Model();
            // Get activation key from DB
            $userActivationKeys = $licenseModel->getUserActivationKeysFromDB($current_user->ID);
            if (!empty($userActivationKeys)) {
                // Get activation keys from RLM Server
                $activationKeys = $licenseModel->getActivationKeyFromRLM($userActivationKeys);
                // Get fulfillments from RLM Server
                $fulfillments = $licenseModel->getFulfillmentFromRLM($userActivationKeys);
                // Get products from RLM Server
                $products = $productModel->getProductFromRLM();
                foreach ($activationKeys as &$activationKey) {
                    $activationKey->fulfillments = array();
                    $activationKey->product = null;
                    $productName = null;
                    if (!empty($fulfillments)) {
                        foreach ($fulfillments as $fulfillment) {
                            if ($activationKey->akey == $fulfillment->akey) {
                                $activationKey->fulfillments[] = $fulfillment;    
                            }    
                        }
                    }
                    if (!empty($products)) {
                        foreach ($products as $product) {
                            if ($product->id == $activationKey->product_id) {
                                $activationKey->product = $product;
                                $productName = $product->name;
                                break;
                            }
                        }
                    }
                    if (!empty($productName)) {
                        if (preg_match('/essential/', strtolower($productName), $match)) {
                            $essentialList[] = $activationKey;    
                        } else if (preg_match('/(team|solo|academic)/', strtolower($productName), $match)) {
                                    $otherList[] = $activationKey;
                                    $found = true;
                             
                        } else if (preg_match('/runtime/', strtolower($productName), $match)) {
                            $runtimeList[] = $activationKey;    
                        }
                    }                               
                }
            }
		    $view = 'public/license/list.php';
        }                        
        $data = array(
            'essential_list' => $essentialList,
            'runtime_list' => $runtimeList,
            'other_list' => $otherList,
        );
        return array(
            'view' => $view,
            'data' => $data
        );
	}
    
    public function registrationActivationKeyFormAction() {
        return array(
            'view' => 'public/license/registration_activation_key_form.php',
            'data' => null
        );    
    }
    
    private function getActivationKey($keys) {
        $activationKey = null;
        if (!empty($keys) && count($keys) == 4) {
            // Check each field
            $isValid = true;
            $keys = $keys;
            foreach ($keys as $key) {
                if (!(!empty($key) && (int)$key)) {
                    $isValid = false;    
                }    
            }
            if ($isValid) {
                $activationKey = str_pad((int)$keys[0], 4, '0', STR_PAD_LEFT);
                $activationKey .= '-' . str_pad((int)$keys[1], 4, '0', STR_PAD_LEFT);
                $activationKey .= '-' . str_pad((int)$keys[2], 4, '0', STR_PAD_LEFT);
                $activationKey .= '-' . str_pad((int)$keys[3], 4, '0', STR_PAD_LEFT);    
            }            
        }
        return $activationKey;
    }
    
    public function registrationActivationKeySaveAction() {
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'hpj-cmlabs-register-activation-key' ) {
            global $current_user, $wpdb;
            if (is_user_logged_in()) {
                $licenseModel = new Hpj_CMLabs_License_Model();
                $activationKey = $this->getActivationKey($_POST['key']);
                if (!empty($activationKey)) {
                    // Check if activation-key exist on DB for another user
                    $data = $licenseModel->getActivationKeyFromDB($activationKey);
                    if (empty($data)) {
                        // Activation Key not exist in any user account
                        $data = $licenseModel->getActivationKeyFromRLM($activationKey);
                        if (!empty($data)) {
                            // Adding Activation Key to current user account
                            $result = $wpdb->insert($wpdb->prefix . 'hpj_cmlabs_activation_key', array(
                                'user_id' => $current_user->ID,
                                'activation_key' => $activationKey,
                            ));
                            if ((int)$result) {
                                // Insert data succeed
                                Hpj_CMLabs_Notice::addMessage('Activation key registered');
                            } else {
                                // An error occured, the insert query failed
                                Hpj_CMLabs_Notice::addError('An error occured');
                            }                      
                        } else {
                            // Activation Key not exist on RLM server
                            Hpj_CMLabs_Notice::addError('The activation key you entered is not valid.');
                        }  
                    } else if (!empty($data) && (int)$data->user_id === (int)$current_user->ID) {
                        // Activation Key already exist in this user account
                        Hpj_CMLabs_Notice::addError('Activation key already exist on your account');    
                    } else {
                        // Activation Key already exist in another user account
                        Hpj_CMLabs_Notice::addError('Activation key already exist in another account');
                    }   
                } else {
                    Hpj_CMLabs_Notice::addError('Activation key is empty or invalid');
                }
            }
            wp_redirect( get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_LICENSES)) );
            exit;    
        }    
    }
    
    public function activationFormAction() {
        global $current_user;
        $datas = array();
        if (is_user_logged_in()) {
            $akey = (!empty($_GET['akey'])) ? $_GET['akey'] : null;
            if (!empty($akey)) {
                if (!empty($_SESSION['hpj_cmlabs_activation_response'])) {
                    $datas = array_merge($datas, $_SESSION['hpj_cmlabs_activation_response']);
                    unset($_SESSION['hpj_cmlabs_activation_response']);    
                }
                
                $licenseModel = new Hpj_CMLabs_License_Model();
                $data = $licenseModel->getUserActivationKeyFromDB($current_user->ID, urldecode($akey));
                if (!empty($data)) {
                    $datas['activation_key'] = $data->activation_key;        
                }                   
            }
        }
        return array(
            'view' => 'public/license/activation_form.php',
            'data' => $datas
        );    
    }
    
    public function activationSaveAction() {
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'hpj-cmlabs-manual-activation' ) {
            global $current_user;
            if (is_user_logged_in()) {
                ob_clean();
                $datas = array('status' => 0, 'messages' => array());
                    
                $akey = (!empty($_POST['akey'])) ? $_POST['akey'] : null;
                $hostId = (!empty($_POST['host_id'])) ? $_POST['host_id'] : null;
                if (!empty($hostId)) {
                    $hostId = str_replace(array('-', ':'), '', $hostId);
                }
                $params = '';
                if (!empty($akey) && trim($akey) && !empty($hostId) && trim($hostId)) {
                    
                    $params = '?akey=' . $akey;
                    $licenseModel = new Hpj_CMLabs_License_Model();
                    $fulfillmentModel = new Hpj_CMLabs_Fulfillment_Model();
                    $activationKey = $licenseModel->getUserActivationKeyFromDB($current_user->ID, urldecode($akey));
                    if (!empty($activationKey)) {
                        // Check if license for this hostId already exist in local
                        $data = $fulfillmentModel->getFulfillmentFromDB($current_user->ID, urldecode($akey), $hostId);
                        $id = null;
                        if (!empty($data)) {
                            $id = $data->id;
                        }
                        $data = $licenseModel->getLicenseFile($akey, $hostId);
                        if (!empty($data)) {
                            // Check there is no error in the response
                            if (!strpos(strtolower($data), 'error')) {
                               if (preg_match('/<pre>(.*)<\/pre>/s',$data, $match)) {
                                   $datas['status'] = true;
                                   $datas['license'] = $match[1];
                               }
                            } else {
                                $datas['messages'][] = __('An error occured', HPJ_CMLABS_I18N_DOMAIN);
                                //Hpj_CMLabs_Notice::addError('An error occured');        
                            }       
                        } else {
                            $datas['messages'][] = __('An error occured', HPJ_CMLABS_I18N_DOMAIN);
                            //Notice::addError('An error occured');    
                        } 
                    } else {
                        $datas['messages'][] = __('Activation key not exist', HPJ_CMLABS_I18N_DOMAIN);
                        //Hpj_CMLabs_Notice::addError('Activation key not exist');   
                    }
                }
                return array(      
                    'data' => $datas
                );
            }
        }                 
    }
    
    public function activationDownloadAction() {
        global $current_user;
        $datas = array();
        if (is_user_logged_in()) {
            $id  = (!empty($_GET['id']) && (int)$_GET['id']) ?  (int)$_GET['id'] : null;
            if (!empty($id)) {
                $fulfillmentModel = new Hpj_CMLabs_Fulfillment_Model();
                $data = $fulfillmentModel->getFulfillmentByIdFromDB($current_user->ID, $id);
                if (!empty($data)) {
                    $datas['license'] = $data->license;
                }  
            }
        }
        return array(
            'view' => 'public/license/activation_display.php',
            'data' => $datas
        );
    }
    
    public function downloadAction() {
        global $current_user;
        if (is_user_logged_in()) {
            $akey  = (!empty($_GET['akey'])) ? urldecode($_GET['akey']) : null;
            $hostId  = (!empty($_GET['hostid'])) ? urldecode($_GET['hostid']) : null;

            
            if (!empty($akey) && !empty($hostId)) {
                $licenseModel = new Hpj_CMLabs_License_Model();
                $fulfillmentModel = new Hpj_CMLabs_Fulfillment_Model();
                $activationKey = $licenseModel->getUserActivationKeyFromDB($current_user->ID, $akey);
                if (!empty($activationKey)) {
                    $data = $fulfillmentModel->getFulfillmentFromRLM($akey, $hostId);
                    if (!empty($data)) {
                        ob_clean();
                        header("Content-type: text/plain",true,200);
                        header("Content-Disposition: attachment; filename=license.lic");
                        header("Pragma: no-cache");
                        header("Expires: 0");
                        header('Connection: close');
                        echo $data->license;
                        exit();
                    } else {
                        Hpj_CMLabs_Notice::addError('License not found');    
                    }
                } else {
                    Hpj_CMLabs_Notice::addError('License not found');        
                }    
            } else {
                Hpj_CMLabs_Notice::addError('License not found');    
            }
        }
        wp_redirect( get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_LICENSES)) );
        exit;
    }
    
    public function generateLicenseAction() {
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'hpj-cmlabs-generate-activation-key' ) {
            global $current_user, $wpdb;
            if (is_user_logged_in()) {                         
                $licenseModel = new Hpj_CMLabs_License_Model();
                $productModel = new Hpj_CMLabs_Product_Model();
                // Get User Email
                $userEmail = $current_user->user_email;
                // Get product Id                              
                $products = $productModel->getProductByNameFromRLM(HPJ_CMLABS_GET_SOFTWARE_NAME);
                $productId = null;
                if (!empty($products)) {
                    if (count($products) > 1) {
                        foreach ($products as $product) {
                            if ((int)$product->prod_id == 0) {
                                $productId = (int)$product->id;
                                break;
                            }
                        }        
                    } else {
                        $productId = (int)$products[0]->id;    
                    }
                }
                // Generate activation key
                if (!empty($productId) && (int)$productId && is_email($userEmail)) {
                    $newActivationKey = null;
                    $return = $licenseModel->generateActivationKeyFromRLM($productId, $userEmail);
                    if (!empty($return) && trim($return) != '') {
                        if (preg_match('/Activation keys: ([0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}){1}/', $return, $match)) {
                            if (!empty($match[1])) {
                                $newActivationKey = $match[1];
                            }    
                        }        
                    }
                    // Save activation key in the user profile
                    if (!empty($newActivationKey)) {
                        // Even if it's a new key, we check if this this one not exist in DB
                        $data = $licenseModel->getActivationKeyFromDB($newActivationKey);
                        if (empty($data)) {
                            $result = $wpdb->insert($wpdb->prefix . 'hpj_cmlabs_activation_key', array(
                                'user_id' => $current_user->ID,
                                'activation_key' => $newActivationKey,
                            ));
                            if ((int)$result) {
                                // Insert data succeed
                                Hpj_CMLabs_Notice::addMessage('A new activation key has been added in your account');
                            } else {
                                // An error occured, the insert query failed
                                Hpj_CMLabs_Notice::addError('generation has failed');
                            }
                        } else if (!empty($data) && (int)$data->user_id === (int)$current_user->ID) {
                            // Activation Key already exist in this user account
                            Hpj_CMLabs_Notice::addError('Activation key already exist on your account');    
                        } else {
                            // Activation Key already exist in another user account
                            Hpj_CMLabs_Notice::addError('Activation key already exist in another account');
                        }   
                    }    
                } else {
                    Hpj_CMLabs_Notice::addError('An error occured');    
                }
                wp_redirect( get_site_url(null, Hpj_CMlabs_Url::getUrlByPageId(HPJ_CMLABS_PAGE_LICENSES)) );
                exit;
            }
        }    
    }

}