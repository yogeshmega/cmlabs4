<?php
defined( 'ABSPATH' ) or die( 'No direct access!' );
include_once(__DIR__ . '/wrapper.php');
class Hpj_CMLabs_RLM_Fulfillment extends Hpj_CMLabs_RLM_Wrapper {

    public $_table = 'licf';
    
    public function __construct() {
        parent::__construct();
        $this->_header['header']['table'] = $this->_table;
    }
    
    public function getByProductId($productId) {
        $return = null;
        if (!empty($productId) && (int)$productId) {
            $this->_header['header']['product_id'] = (int)$productId;
            $response = $this->getRequest();
            if (!empty($response) && $response[0]->status == 'OK') {
                $return = $response[1];    
            }
        }
        return $return;
    }
    
    public function getByActivationKeyAndHostId($activationKey, $hostId) {
        $return = null;
        if (!empty($activationKey) && !empty($hostId)) {
            $this->_header['header']['sql_where'] = 'akey = "' . $activationKey . '" AND license_hostid = "' . $hostId . '"';
            $response = $this->getRequest();
            if (!empty($response) && $response[0]->status == 'OK') {
                $return = $response[1];    
            }
        }
        return $return;
    }
    
    public function deleteFulfillment( $activationKey ) {
        $return = null;
        if (!empty($activationKey) ) {
            $datas = array(
                'akey' => $activationKey,                
            );                   
            $response = $this->deleteRequest($datas);
            if (!empty($response) && $response[0]->status == 'OK') {
                $return = $response[0]->message;    
            }    
        }
        return $return;  
    } 
    
}