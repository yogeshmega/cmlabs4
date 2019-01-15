<?php
defined( 'ABSPATH' ) or die( 'No direct access!' );

include_once(__DIR__ . '/model.php');                        
include_once(__DIR__ . '/../library/rlm/fulfillment.php');       

class Hpj_CMLabs_Fulfillment_Model extends Hpj_CMLabs_Model {

    public function getFulfillmentFromDB($userId, $activationKey, $hostId) {
        global $wpdb;
        $fulfillment = null;                                 
        if (!empty($userId) && (int)$userId && !empty($activationKey) && !empty($hostId)) {
            $data = $wpdb->get_row(
                $wpdb->prepare(
                    'SELECT f.* FROM ' . $wpdb->prefix . 'hpj_cmlabs_fulfillment f
                    INNER JOIN ' . $wpdb->prefix . 'hpj_cmlabs_activation_key ak ON (ak.id = f.activation_key_id)
                    WHERE ak.user_id = %d AND ak.activation_key = %s AND f.host_id = %s;',
                    (int)$userId, $activationKey, $hostId
                )
            );                                           
            if (!empty($data)) {
                $fulfillment = $data;
            }    
        }
        return $fulfillment;    
    }
    
    public function getFulfillmentByIdFromDB($userId, $id) {
        global $wpdb;
        $fulfillment = null;
        if (!empty($userId) && (int)$userId && !empty($id) && (int)$id) {
            $data = $wpdb->get_row(
                $wpdb->prepare(
                    'SELECT f.* FROM ' . $wpdb->prefix . 'hpj_cmlabs_fulfillment f
                    INNER JOIN ' . $wpdb->prefix . 'hpj_cmlabs_activation_key ak ON (ak.id = f.activation_key_id)
                    WHERE ak.user_id = %d AND f.id = %s;',
                    (int)$userId, $id
                )
            );
            if (!empty($data)) {
                $fulfillment = $data;
            }    
        }
        return $fulfillment;    
    }
    
    public function getFulfillmentFromRLM($activationKey, $hostId) {
        $fulfillment = array();
        if (!empty($activationKey) && !empty($hostId)) {
            $fulfillmentClass = Hpj_CMLabs_RLM_Fulfillment::getInstance();
            $fulfillment = $fulfillmentClass->getByActivationKeyAndHostId($activationKey, $hostId);
            if (!empty($fulfillment)) {
                $fulfillment = $fulfillment[0];
            }
        }
        return $fulfillment;    
    }
    
    public function insertOrUpdateFulfillment($activationKeyId, $hostId, $license, $id = null) {
        global $wpdb;
        if (!empty($activationKeyId) && (int)$activationKeyId && !empty($hostId) && !empty($license)) {
            $data = array(
                'activation_key_id' => $activationKeyId,
                'host_id' => $hostId,
                'license' => $license,
            );
            if (!empty($id) && (int)$id) {
                $result = $wpdb->update($wpdb->prefix . 'hpj_cmlabs_fulfillment', $data, array('id' => (int)$id), array('%d', '%s', '%s'),  array('%d'));    
            } else {
                $result = $wpdb->insert($wpdb->prefix . 'hpj_cmlabs_fulfillment', $data, array('%d', '%s', '%s'));    
            }
            if (isset($result) && $result !== false) {
                if (!empty($id) && (int)$id) {
                    return $id;    
                } else {
                    return $wpdb->insert_id;    
                }
            }    
        }
        return false;   
    }
}