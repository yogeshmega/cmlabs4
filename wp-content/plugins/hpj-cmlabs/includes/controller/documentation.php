<?php
defined( 'ABSPATH' ) or die( 'No direct access!' );

include_once(__DIR__ . '/controller.php');

class Hpj_CMLabs_Documentation_Controller extends Hpj_CMLabs_Controller {

    public function listAction() {
        return array(
            'view' => 'public/documentation/list.php',
            'data' => null
        );
    }
    
}
