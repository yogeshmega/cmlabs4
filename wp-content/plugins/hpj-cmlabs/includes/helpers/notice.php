<?php

defined( 'ABSPATH' ) or die( 'No direct access!' );

class Hpj_CMLabs_Notice {

    private static $_errors = array();
    private static $_messages = array();
    
    private static function add($msg, $type) {
        if (!empty($msg) && !empty($type)) {
            if (!session_id()) {
                session_start();
            }
            if (empty($_SESSION['hpj_cmlabs_notice_' . $type])) {
                $_SESSION['hpj_cmlabs_notice_' . $type] = array();   
            }
            $_SESSION['hpj_cmlabs_notice_' . $type][] = (string)$msg;
        }    
    }
    
    public static function addMessage($msg) {
        self::add($msg, 'message');    
    }
    
    public static function addError($msg) {
        self::add($msg, 'error');    
    }
    
    private static function get($type) {
        $return = null;
        if (!empty($type)) {
            if (!session_id()) {
                session_start();
            }
            if (!empty($_SESSION['hpj_cmlabs_notice_' . $type])) { 
                $return = $_SESSION['hpj_cmlabs_notice_' . $type];
                $_SESSION['hpj_cmlabs_notice_' . $type] = array();
            }
        }                  
        return $return;            
    }
    
    public static function getErrors() {
        return self::get('error');            
    }
    
    public static function getMessages() {
        return self::get('message');    
    }   
    
}
  
?>
