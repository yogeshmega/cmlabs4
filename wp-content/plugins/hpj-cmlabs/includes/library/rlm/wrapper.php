<?php
defined( 'ABSPATH' ) or die( 'No direct access!' );                                                   
include_once(__DIR__ . '/../vendor/Httpful/Bootstrap.php');
include_once(__DIR__ . '/../vendor/Httpful/Http.php');
include_once(__DIR__ . '/../vendor/Httpful/Request.php');
include_once(__DIR__ . '/FileLogger.php');
use gehaxelt\fileLogger\FileLogger;

class Hpj_CMLabs_RLM_Wrapper {

    public static $_instance = array();
    
    public $_header = null;
    
    public $_url = HPJ_CMLABS_RLM_URL;

    public static $_logger = null;

    public function __construct() {
        if (is_user_logged_in()) {
            $this->_header = array(
                'header' => array(
                    'user' => HPJ_CMLABS_RLM_USER,
                    'password' => HPJ_CMLABS_RLM_PASSWORD,
                    'table' => null,
                )
            );
     }
    }
   
    public static function log($message) {
        if ( self::$_logger == null ) {
            self::$_logger = new FileLogger(__DIR__.'/../../../../../hpj-logs/rlm.log');
            self::$_logger->log('New Hpj_CMLabs_RLM_Wrapper', FileLogger::NOTICE);
        }
        self::$_logger->log($message, FileLogger::NOTICE);
    }
 
    public static function getInstance() {
        $className = get_called_class();
        if (empty(self::$_instance[$className])) {
            self::$_instance[$className] = new $className();    
        }                                          
        return self::$_instance[$className];
    }
    

    private function doRequest($method, $datas, $url = null) {
        /*if (!empty($this->_url)) {
            
        } else {
            
        }*/
        if (empty($url)) {
            $url = $this->_url;
        }
        $request = null;
        switch ($method) {
            case 'get':
                $request = \Httpful\Request::get($url);
            break;

            case 'post':
                $request = \Httpful\Request::post($url);
            break;

            case 'put':
                $request = \Httpful\Request::put($url);
            break;

            case 'delete':
                $request = \Httpful\Request::delete($url);
            break;
        }
        if (!empty($request)) {
            #$dataAsString = print_r($datas, true);
            #$this->log('Hpj_CMLabs_RLM_Wrapper::doRequest '.$url.' '.$method.' '.$dataAsString);
            $response = $request->body($datas, 'application/json')
                ->expectsJson()
                ->authenticateWith(HPJ_CMLABS_USER, HPJ_CMLABS_PASSWORD)
                ->send();
            return $response->body;
        }
        return null;
    }

    public function getRequest($url = null) {
        return $this->doRequest('get', $this->_header, $url);
    }

    public function postRequest($datas, $url = null) {                            
        return $this->doRequest('post', array_merge($this->_header, array('data' => array($datas))), $url);
    }

    public function putRequest($datas, $url = null) {
        return $this->doRequest('put', array_merge($this->_header, array('data' => array($datas))), $url);
    }

    public function deleteRequest($datas, $url = null) {
	$this->_header['header']['akey'] = $datas['akey'];
        return $this->doRequest('delete', $this->_header, $url);
    }
    
    public function get() {
        $return = null;
        $response = $this->getRequest();
        if (!empty($response) && $response[0]->status == 'OK') {
            $return = $response[1];    
        }
        return $return;
    }

    public function post() {
        $datas = array('data' => array());
        $return = null;
        $response = $this->postRequest($datas);
        if (!empty($response) && $response[0]->status == 'OK') {
            $return = $response[1];    
        }
        return $return;
    }

    public function put() {
        $datas = array('data' => array());
        $return = null;
        $response = $this->putRequest($datas);
        if (!empty($response) && $response[0]->status == 'OK') {
            $return = $response[1];    
        }
        return $return;
    }

    public function delete() {
        $return = null;
        $response = $this->deleteRequest($datas);
        if (!empty($response) && $response[0]->status == 'OK') {
            $return = $response[1];    
        }
        return $return;
    }
    
    public function getByActivationKey($activationKey) {
        $return = null;
        if (in_array($this->_table, array('keyd', 'licf'))) {
            if (!empty($activationKey)) {
                if (is_array($activationKey)) {
                    $tmpAkey = '';
                    foreach ($activationKey as $index => $aKey) {
                        if ($index > 0) $tmpAkey .= ',';
                        $tmpAkey .= '"' . $aKey . '"';    
                    }
                    $activationKey = $tmpAkey;
                } else {
                    $activationKey = '"' . $activationKey . '"';   
                }
                $this->_header['header']['sql_where'] = 'akey IN (' . $activationKey . ')';
                $response = $this->getRequest();
                if (!empty($response) && $response[0]->status == 'OK') {
                    $return = $response[1];    
                }
            }
        }
        return $return;
    }

}
