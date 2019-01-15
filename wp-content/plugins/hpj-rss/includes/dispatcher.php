<?php

class Hpj_RSS_Dispatcher {

    const PREFIX = 'Hpj_RSS_';
    const SUFFIX_CONTROLLER = '_Controller';
    const SUFFIX_METHOD = 'Action';
    
    public static function dispatch($controller, $method) {
        // Get class
        $controller = strtolower($controller);
        if (file_exists(__DIR__ . '/controller/' . $controller . '.php')) {
            include_once(__DIR__ . '/controller/' . $controller . '.php');
            // Format by Upper first letter
            $controller = strtoupper(substr($controller, 0, 1)) . substr($controller, 1);
            $controller = self::PREFIX . $controller . self::SUFFIX_CONTROLLER;
            if (class_exists($controller)) {
                $instance = $controller::getInstance();
                if (!empty($instance)) {
                    // Get method
                    if (!strpos(strtolower($method), strtolower(self::SUFFIX_METHOD))) {
                        $method .= self::SUFFIX_METHOD;
                    }
                    if (method_exists($instance, $method)) {
                        $return = $instance->$method();
                        // Get view
                        if (!empty($return['view'])) {
                            if (file_exists(__DIR__ . '/view/' . $return['view'])) {
                                if (!empty($return['data'])) {
                                    extract($return['data']);
                                }
                                ob_start();
                                include_once(__DIR__ . '/view/' . $return['view']);
                                $view = ob_get_clean();
                                echo $view;
                            } else {
                                // display nothing
                            }
                        } else {
                            if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
                                if (!empty($return['data'])) {
                                    ob_clean();
                                    echo json_encode($return['data']);
                                    exit;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

}