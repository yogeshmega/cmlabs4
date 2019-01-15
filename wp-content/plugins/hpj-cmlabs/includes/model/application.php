<?php
defined( 'ABSPATH' ) or die( 'No direct access!' );

include_once(__DIR__ . '/model.php');       

class Hpj_CMLabs_Application_Model extends Hpj_CMLabs_Model {

    public function getApplicationFromDB() {
        global $wpdb;
        $applications = null;
        $data = $wpdb->get_results('SELECT a.* FROM ' . $wpdb->prefix . 'hpj_cmlabs_application a');                                           
        if (!empty($data)) {
            $applications = $data;
        }    
        return $applications;    
    }
    
    public function getApplicationByNameFromDB($name, $version) {
        $application = null;
        if (!empty($name) && !empty($version)) {
            global $wpdb;
            $data = $wpdb->get_results('SELECT a.* FROM ' . $wpdb->prefix . 'hpj_cmlabs_application a WHERE name = "' . esc_sql($name) . '" AND version = "' . esc_sql($version) . '"');                                           
            if (!empty($data)) {
                $application = $data;
            }                   
        }
        return $application;
    }
    
    public function getApplicationByIdFromDB($id) {
        global $wpdb;
        $download = null;
        if (!empty($id) && (int)$id) {
            $sql = 'SELECT a.* FROM ' . $wpdb->prefix . 'hpj_cmlabs_application a'
                . ' WHERE a.id = ' . (int)$id;
            $data = $wpdb->get_results($sql);                              
            if (!empty($data)) {
                $download = $data[0];
            }
        }   
        return $download;    
    }
    
    public function updateApplication($name, $version, $edition, $published, $category, $displayorder, $id) {
        if ($this->insertOrUpdateApplication($name, $version, $edition, $published, $category, $displayorder, $id)) {
            return true;
        }
        return false;
    }
    
    public function insertApplication($name, $version, $edition, $published, $category, $displayorder) {
        return $this->insertOrUpdateApplication($name, $version, $edition, $published, $category, $displayorder);
    }
    
    private function insertOrUpdateApplication($name, $version, $edition, $published, $category, $displayorder, $id = null) {
        global $wpdb;
        if (!empty($name) && !empty($edition)) {
            $data = array(
                'name' => $name,
				'category' => $category,
                'version' => $version,
                'edition' => $edition,
				'displayorder' => (int)$displayorder,
                'published' => (int)$published
            );
			
            if (!empty($id)) {
                if ((int)$id) {
                    return $wpdb->update($wpdb->prefix . 'hpj_cmlabs_application', $data, array('id' => $id), array('%s', '%s', '%s', '%s', '%d', '%d' ), array('%d'));    
                }
            } else {
                $data['cdate'] = date('Y-m-d h:i:s');
                $result = $wpdb->insert($wpdb->prefix . 'hpj_cmlabs_application', $data, array('%s', '%s', '%s', '%s', '%d', '%d', '%s' ));
                if (isset($result) && $result !== false) {
                    return $wpdb->insert_id;
                }
            }
        }
        return false;    
    }
    
    public function deleteApplication($id) {
        global $wpdb;     
        if (!empty($id) && (int)$id) {
            $isOk = true;
            $downloadModel = new Hpj_CMLabs_Download_Model();
            $downloads = $downloadModel->getDownloadByApplicationId($id);
            if (!empty($downloads)) {
                if (!$downloadModel->deleteDownloadByApplicationId($id)) {
                    $isOk = false;   
                }    
            }
            if ($isOk) {
                if ($wpdb->delete($wpdb->prefix . 'hpj_cmlabs_application', array('id' => (int)$id))) {
                    return true;
                }
            }
        }
        return false;    
    }
    
}
?>
