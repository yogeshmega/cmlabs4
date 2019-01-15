<?php
defined( 'ABSPATH' ) or die( 'No direct access!' );

class Hpj_CMLabs_Form {
    
    public static function getFormData($url) {
        $return = array();
        if (!empty($url)) {
            if (!session_id()) {
                session_start();
            }
            if (!empty($_SESSION['hpj_cmlabs_form']) && !empty($_SESSION['hpj_cmlabs_form'][$url])) { 
                $return = $_SESSION['hpj_cmlabs_form'][$url];
                $_SESSION['hpj_cmlabs_form'][$url] = array();
            }
        }
        return $return;
    }
    
    public function addFormData($url, $formDatas) {
        if (!empty($url) && !empty($formDatas)) {
            if (!session_id()) {
                session_start();
            }
            if (empty($_SESSION['hpj_cmlabs_form'])) {
                $_SESSION['hpj_cmlabs_form'] = array();
            }
            $_SESSION['hpj_cmlabs_form'][$url] = $formDatas;   
        }
    }
    
    public function cleanFormData($url) {
        if (!empty($url)) {
            if (!session_id()) {
                session_start();
            }
            if (!empty($_SESSION['hpj_cmlabs_form']) && !empty($_SESSION['hpj_cmlabs_form'][$url])) { 
                $_SESSION['hpj_cmlabs_form'][$url] = array();
            }   
        }
    }
    
}
?>
