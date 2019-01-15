<?php
defined( 'ABSPATH' ) or die( 'No direct access!' );

include_once(__DIR__ . '/model.php');
include_once(__DIR__ . '/../library/rlm/activation-key.php');
include_once(__DIR__ . '/../library/rlm/product.php');
include_once(__DIR__ . '/../library/rlm/fulfillment.php');
include_once(__DIR__ . '/../library/rlm/wrapper.php');

class Hpj_CMLabs_License_Model extends Hpj_CMLabs_Model {

	public function getUserActivationKeysFromDB($userId) {
        global $wpdb;
       
        $activationKeys = array();                                 
        if (!empty($userId) && (int)$userId) {
            $datas = $wpdb->get_results($wpdb->prepare('SELECT id, activation_key FROM ' . $wpdb->prefix . 'hpj_cmlabs_activation_key WHERE user_id = %d;', (int)$userId));
            if (!empty($datas)) {
                foreach ($datas as $data) {
                    $activationKeys[] = $data->activation_key;
                }    
            }    
        }
        return $activationKeys;
    }
    
    public function getUserActivationKeyFromDB($userId, $activationKey) {
        global $wpdb;
        $activationKeys = null;                                 
        if (!empty($userId) && (int)$userId && !empty($activationKey)) {
            $data = $wpdb->get_row($wpdb->prepare('SELECT id, activation_key FROM ' . $wpdb->prefix . 'hpj_cmlabs_activation_key WHERE user_id = %d AND activation_key = %s;', (int)$userId, $activationKey));
            if (!empty($data)) {
                $activationKeys = $data;
            }    
        }
        
        #Hpj_CMLabs_RLM_Wrapper::log('Hpj_CMLabs_License_Model::getUserActivationKeyFromDB -- key: '.implode(",",$activationKeys));

        return $activationKeys;
    }
    
    public function getActivationKeyFromDB($userActivationKey) {
        global $wpdb;               
        $activationKey = null;                                 
        if (!empty($userActivationKey)) {
            $datas = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'hpj_cmlabs_activation_key WHERE activation_key = "' . esc_attr($userActivationKey) . '";');
            if (!empty($datas)) {
                $activationKey = $datas[0];
            }                                
        }
        return $activationKey;
    }
    
    public function getActivationKeyFromRLM($userActivationKeys) {
        $activationKeys = array();
        if (!empty($userActivationKeys)) {
            $activationKeyClass = Hpj_CMLabs_RLM_Activation_Key::getInstance();
            $activationKeys = $activationKeyClass->getByActivationKey($userActivationKeys);
        }
        return $activationKeys;
    }
    
    public function getFulfillmentFromRLM($userActivationKeys) {
        $activationKeys = array();
        if (!empty($userActivationKeys)) {
            $fulfillmentClass = Hpj_CMLabs_RLM_Fulfillment::getInstance();
            $activationKeys = $fulfillmentClass->getByActivationKey($userActivationKeys);
        }
        return $activationKeys;
    }
    
    public function getLicenseFile($activationKey, $hostId) {
        $return = null;
        #Hpj_CMLabs_RLM_Wrapper::log('Hpj_CMLabs_License_Model::getLicenseFile');
        if (!empty($activationKey) && !empty($hostId)) {
            include_once(__DIR__ . '/../library/vendor/Httpful/Bootstrap.php');
            include_once(__DIR__ . '/../library/vendor/Httpful/Http.php');
            include_once(__DIR__ . '/../library/vendor/Httpful/Request.php');
           
            #Hpj_CMLabs_RLM_Wrapper::log('Hpj_CMLabs_License_Model::getLicenseFile - akey='.$activationKey.' hostid='.$hostId);

            $request = \Httpful\Request::post(HPJ_CMLABS_RLM_URL_MANUAL_ACTIVATION);
            $response = $request->body('akey=' . urlencode($activationKey) . '&hostid=' . urlencode($hostId) . '&count=1')
                ->send();
            $return = $response->body;
            #Hpj_CMLabs_RLM_Wrapper::log('Hpj_CMLabs_License_Model::getLicenseFile - response='.$return);
        }
        return $return;
    }
    
    public function generateActivationKeyFromRLM($productId, $userEmail) {
        $return = null;
        if (!empty($productId) && (int)$productId &&  !empty($userEmail) && trim($userEmail) != '') {
            $activationKeyClass = Hpj_CMLabs_RLM_Activation_Key::getInstance();
            $return = $activationKeyClass->generateActivationKey($productId, $userEmail);
            //$return = '1 rows added. Activation keys: 7856-1104-3663-4090 ';
        }
        return $return;    
    }

}
