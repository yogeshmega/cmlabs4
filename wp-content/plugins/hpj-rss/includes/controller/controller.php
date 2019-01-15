<?php
defined( 'ABSPATH' ) or die( 'No direct access!' );

abstract class Hpj_RSS_Controller {

    private static $_instance = array();

    private function __construct() {}

    public static function getInstance() {
        $className = get_called_class();
        if (empty(self::$_instance[$className])) {
            
            self::$_instance[$className] = new $className();
        }
        return self::$_instance[$className];
    }

}