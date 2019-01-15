<?php
defined( 'ABSPATH' ) or die( 'No direct access!' );
include_once(__DIR__ . '/wrapper.php');
class Hpj_CMLabs_RLM_Activation_Key extends Hpj_CMLabs_RLM_Wrapper {

    public $_table = 'keyd';
    
    public function __construct() {
        parent::__construct();
        $this->_header['header']['table'] = $this->_table;
    }
    
    public function generateActivationKey($productId, $userEmail) {
        $return = null;
        if (!empty($productId) && (int)$productId &&  !empty($userEmail) && trim($userEmail) != '') {
            $datas = array(
                'product_id' => (int)$productId,
                'notes' => $userEmail,
                'type' => 0,
                'count' => 1,
                'rehosts' => 0,
                'numkeys' => 1,
                'kver_type' => 0,
                'active' => 1
            );                   
            $response = $this->postRequest($datas);
            if (!empty($response) && $response[0]->status == 'OK') {
                $return = $response[0]->message;    
            }    
        }
        return $return;  
    } 
   
}