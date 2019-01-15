<?php
defined( 'ABSPATH' ) or die( 'No direct access!' );

include_once(__DIR__ . '/controller.php');

class Hpj_CMLabs_Welcome_Controller extends Hpj_CMLabs_Controller {

    public function welcomeAction() {
        return array(
            'view' => 'public/user/welcome.php',
            'data' => NULL
        );
    }

    public function welcomeSaveAction() {
    }

}
