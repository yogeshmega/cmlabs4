<?php
defined( 'ABSPATH' ) or die( 'No direct access!' );

include_once(__DIR__ . '/model.php');       

class Hpj_CMLabs_Download_Model extends Hpj_CMLabs_Model {

    public function getDownloadsFromDB($where = null, $order = null, $latest = false) {
        global $wpdb;
        $downloads = null;
		if ( $latest ) {
			$appname = 'a.category';
		} else {
			$appname = 'a.name';
		}
		
        $sql = 'SELECT d.*, ' . $appname . ' AS application_name, a.version AS application_version FROM ' . $wpdb->prefix . 'hpj_cmlabs_download d'
            . ' INNER JOIN ' . $wpdb->prefix . 'hpj_cmlabs_application a ON (d.application_id = a.id)';
        if (!empty($where) && trim($where) != '') {
            $sql .= ' WHERE ' . $where;    
        }    
		if ( $latest ) {
			$sql .= ' ORDER BY a.displayorder ASC, d.name ASC';
		} else {
			$sql .= ' ORDER BY a.name DESC, d.name ASC';
		}
        $data = $wpdb->get_results($sql);                              
        if (!empty($data)) {
            $downloads = $data;
        }    
        return $downloads;    
    }
    
    public function getDownloadByIdFromDB($id) {
        global $wpdb;
        $download = null;
        if (!empty($id) && (int)$id) {
            $sql = 'SELECT d.*, a.name AS application_name, a.version AS application_version FROM ' . $wpdb->prefix . 'hpj_cmlabs_download d'
                . ' INNER JOIN ' . $wpdb->prefix . 'hpj_cmlabs_application a ON (d.application_id = a.id)'
                . ' WHERE d.id = ' . (int)$id;
            $data = $wpdb->get_results($sql);                              
            if (!empty($data)) {
                $download = $data[0];
            }
        }   
        return $download;    
    }
    
    public function getDownloadByApplicationId($applicationId) {
        global $wpdb;
        $download = null;
        if (!empty($applicationId) && (int)$applicationId) {
            $sql = 'SELECT d.*, a.name AS application_name, a.version AS application_version FROM ' . $wpdb->prefix . 'hpj_cmlabs_download d'
                . ' INNER JOIN ' . $wpdb->prefix . 'hpj_cmlabs_application a ON (d.application_id = a.id)'
                . ' WHERE d.application_id = ' . (int)$applicationId;
            $data = $wpdb->get_results($sql);                              
            if (!empty($data)) {
                $download = $data;
            }
        }   
        return $download;    
    }
    
    public function updateDownload($name, $link, $platform, $size, $description, $requirement, $applicationId, $published, $date, $id) {
        $return = $this->insertOrUpdateDownload($name, $link, $platform, $size, $description, $requirement, $applicationId, $published, $date, $id);
        if (!empty($return) && (int)$return) {
            return true;
        }
        return false;    
    }
    
    public function insertDownload($name, $link, $platform, $size, $description, $requirement, $applicationId, $published ) {
        return $this->insertOrUpdateDownload($name, $link, $platform, $size, $description, $requirement, $applicationId, $published );    
    }
    
    private function insertOrUpdateDownload($name, $link, $platform, $size, $description, $requirement, $applicationId, $published, $date = null, $id = null) {
        global $wpdb;
        if (!empty($name) && !empty($link) && !empty($requirement) && !empty($applicationId) && (int)$applicationId) {
            $data = array(
                'name' => $name,
                'link' => $link,
                'platform' => $platform,
                'size' => $size,
                'description' => $description,
                'requirement' => $requirement,
                'application_id' => $applicationId,
                'published' => (int)$published
            );
            if (!empty($id) && (int)$id) {
                if (!empty($date)) {
                    $data['cdate'] = date('Y-m-d h:i:s', strtotime($date));
                }
                return $result = $wpdb->update($wpdb->prefix . 'hpj_cmlabs_download', $data, array('id' => $id), array('%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d'), array('%d'));    
            } else {
                $data['cdate'] = date('Y-m-d h:i:s');
                $result = $wpdb->insert($wpdb->prefix . 'hpj_cmlabs_download', $data, array('%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%s'));
                if (isset($result) && $result !== false) {
                    return $wpdb->insert_id;
                }
            }
        }
        return false;    
    }
    
    public function deleteDownload($id) {
        global $wpdb;     
        if (!empty($id) && (int)$id) {
            if ($wpdb->delete($wpdb->prefix . 'hpj_cmlabs_download', array('id' => (int)$id))) {
                return true;        
            }    
        }
        return false;    
    }
    
    public function deleteDownloadByApplicationId($applicationId) {
        global $wpdb;     
        if (!empty($applicationId) && (int)$applicationId) {
            if ($wpdb->delete($wpdb->prefix . 'hpj_cmlabs_download', array('application_id' => (int)$applicationId))) {
                return true;        
            }    
        }
        return false;    
    }
}