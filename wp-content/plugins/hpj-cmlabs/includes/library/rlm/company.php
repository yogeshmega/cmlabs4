<?php
defined( 'ABSPATH' ) or die( 'No direct access!' );
include_once(__DIR__ . '/wrapper.php');
class Hpj_CMLabs_RLM_Company extends Hpj_CMLabs_RLM_Wrapper {

    public $_table = 'company';
    
    public function __construct() {
        parent::__construct();
        $this->_header['header']['table'] = $this->_table;
    }    

}