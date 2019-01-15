<?php
defined( 'ABSPATH' ) or die( 'No direct access!' );

include_once(__DIR__ . '/controller.php');
include_once(__DIR__ . ' /../model/download.php');

class Hpj_CMLabs_Download_Controller extends Hpj_CMLabs_Controller {

    public function listAction() {
        $downloadModel = new Hpj_CMLabs_Download_Model();
		if ( isset( $_GET['previous'] ) ) {
			$downloads = $downloadModel->getDownloadsFromDB('a.published = 1 AND d.published = 1 AND a.version = 2');
		} else {
		    $downloads = $downloadModel->getDownloadsFromDB('a.published = 1 AND d.published = 1 AND a.version = 1', NULL, true);
		}
        
        $versionLabels = array(
            1 => __('Latest', HPJ_CMLABS_I18N_DOMAIN),
            2 => __('Previous', HPJ_CMLABS_I18N_DOMAIN),
			3 => __('Staging', HPJ_CMLABS_I18N_DOMAIN),
        );
        
        $newDownloads = array();
        if (!empty($downloads)) {			
            foreach ($downloads as $download) {
				if ( isset( $_GET['previous'] ) ) {
					$key = $download->application_name;
				} else {
					$key = $download->application_name;
				}
				
                if (empty($newDownloads[$key])) {
                    $newDownloads[$key] = array();    
                }
                $newDownloads[$key][] = $download;   
            }    
        }
        
        $datas = array(
            'downloads' => $newDownloads
        );
        return array(
            'view' => 'public/download/list.php',
            'data' => $datas
        );
    }                             
    
}
