<?php
defined( 'ABSPATH' ) or die( 'No direct access!' );
include_once(__DIR__ . '/wrapper.php');
class Hpj_CMLabs_RLM_Product extends Hpj_CMLabs_RLM_Wrapper {

    public $_table = 'prod';
    
    public function __construct() {
        parent::__construct();
        $this->_header['header']['table'] = $this->_table;
    }
    
    public function getProductByName($productName) {
        $return = null;
        if (!empty($productName) && trim($productName) != '') {
            $this->_header['header']['table'] = $this->_table;
            $this->_header['header']['name'] = $productName;
            $response = $this->getRequest();
            if (!empty($response) && $response[0]->status == 'OK') {
                $return = $response[1];    
            }
        }
        return $return;
    }
}