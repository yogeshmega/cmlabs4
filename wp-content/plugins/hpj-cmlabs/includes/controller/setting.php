<?php
defined( 'ABSPATH' ) or die( 'No direct access!' );

include_once(__DIR__ . '/controller.php');
include_once(__DIR__ . ' /../model/download.php');
include_once(__DIR__ . ' /../model/application.php');

class Hpj_CMLabs_Setting_Controller extends Hpj_CMLabs_Controller {

    public function generalAction() {
        $pages = get_pages('array');
        $datas = array(
            'pages' => $pages
        );
        return array(
            'view' => 'admin/setting/general.php',
            'data' => $datas
        );
    }
    
    public function downloadsAction() {
        $downloadModel = new Hpj_CMLabs_Download_Model();
        $applicationModel = new Hpj_CMLabs_Application_Model();
        $downloads = $downloadModel->getDownloadsFromDB();
        $applications = $applicationModel->getApplicationFromDB();
        
        $form_datas = Hpj_CMLabs_Form::getFormData(admin_url(HPJ_CMLABS_ADMIN_URL_DOWNLOAD));
        if (empty($form_datas) && !empty($_GET['mode']) && $_GET['mode'] = 'edit' && !empty($_GET['id']) && (int)$_GET['id']) {
            $download = $downloadModel->getDownloadByIdFromDB((int)$_GET['id']);
            if (!empty($download)) {
                $form_datas['hpj_cmlabs_setting_download_id'] = (int)$download->id;
                $form_datas['hpj_cmlabs_setting_download_name'] = htmlspecialchars(stripslashes($download->name));
                $form_datas['hpj_cmlabs_setting_download_url'] = htmlspecialchars(stripslashes($download->link));
                $form_datas['hpj_cmlabs_setting_download_platform'] = htmlspecialchars(stripslashes($download->platform));
                $form_datas['hpj_cmlabs_setting_download_size'] = htmlspecialchars(stripslashes($download->size));
                $form_datas['hpj_cmlabs_setting_download_description'] = htmlspecialchars(stripslashes($download->description));
                $form_datas['hpj_cmlabs_setting_download_requirement'] = htmlspecialchars(stripslashes($download->requirement));
                $form_datas['hpj_cmlabs_setting_download_application_id'] = (int)$download->application_id;
                $form_datas['hpj_cmlabs_setting_download_published'] = (int)$download->published;
                $form_datas['hpj_cmlabs_setting_download_date'] = htmlspecialchars(stripslashes($download->cdate));
            }
        }
        
        $versionLabels = array(
            1 => __('Latest', HPJ_CMLABS_I18N_DOMAIN),
            2 => __('Previous', HPJ_CMLABS_I18N_DOMAIN),
        );                       
        
        $newDownloads = array();
        if (!empty($downloads)) {
            foreach ($downloads as $download) {
                $key = $download->application_name . ((!empty($download->application_version) && trim($download->application_version) != '') ? ' (' . $versionLabels[$download->application_version] . ')' : '');
                if (empty($newDownloads[$key])) {
                    $newDownloads[$key] = array();    
                }
                $newDownloads[$key][] = $download;   
            }    
        }
        
        $datas = array(
            'downloads' => $newDownloads,
            'applications' => $applications,
            'form_datas' => $form_datas
        );
        return array(
            'view' => 'admin/setting/downloads.php',
            'data' => $datas
        );
    }
    
    public function downloadsSaveAction() {
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'hpj_cmlabs_setting_download_save' ) {
            if (is_user_logged_in()) {
                $isEdit = false;
                $isOk = false;
                $url = admin_url(HPJ_CMLABS_ADMIN_URL_DOWNLOAD);
                Hpj_CMLabs_Form::addFormData($url, $_POST);
                 
                $id = (!empty( $_POST['hpj_cmlabs_setting_download_id'] )) ? $_POST['hpj_cmlabs_setting_download_id'] : null;
                $fileName = (!empty( $_POST['hpj_cmlabs_setting_download_name'] )) ? $_POST['hpj_cmlabs_setting_download_name'] : null;
                $fileUrl = (!empty( $_POST['hpj_cmlabs_setting_download_url'] )) ? $_POST['hpj_cmlabs_setting_download_url'] : null;
                $platform = (!empty( $_POST['hpj_cmlabs_setting_download_platform'] )) ? $_POST['hpj_cmlabs_setting_download_platform'] : null;
                $size = (!empty( $_POST['hpj_cmlabs_setting_download_size'] )) ? $_POST['hpj_cmlabs_setting_download_size'] : '';
                $applicationId = (!empty( $_POST['hpj_cmlabs_setting_download_application_id'] )) ? $_POST['hpj_cmlabs_setting_download_application_id'] : null;
                $description = (!empty( $_POST['hpj_cmlabs_setting_download_description'] )) ? $_POST['hpj_cmlabs_setting_download_description'] : '';
                $requirement = (!empty( $_POST['hpj_cmlabs_setting_download_requirement'] )) ? $_POST['hpj_cmlabs_setting_download_requirement'] : '';
                $published = (!empty( $_POST['hpj_cmlabs_setting_download_published'] )) ? $_POST['hpj_cmlabs_setting_download_published'] : '';
                $date = (!empty( $_POST['hpj_cmlabs_setting_download_date'] )) ? $_POST['hpj_cmlabs_setting_download_date'] : null;
                
                if (!empty($id)) {
                    $isEdit = true;
                }
                                                                    
                $isError = false;
                $addNewApplication = false;
                if (empty($fileName)) {
                    $isError = true;
                    Hpj_CMLabs_Notice::addError('Filename is required');    
                }
                if (empty($fileUrl)) {
                    $isError = true;
                    Hpj_CMLabs_Notice::addError('Link is required');    
                }
                /*if (empty($platform)) {
                    $isError = true;
                    Hpj_CMLabs_Notice::addError('Platform is required');    
                }*/
                if (empty($size)) {
                    $isError = true;
                    Hpj_CMLabs_Notice::addError('Size is required');    
                }
                if (empty($applicationId) || !(int)$applicationId) {
                    $isError = true;
                    Hpj_CMLabs_Notice::addError('Application ID is required');    
                }
                if (empty($requirement)) {
                    $isError = true;
                    Hpj_CMLabs_Notice::addError('Requirement is required');    
                }
                if (!empty($id) && (int)$id) {
                    if (!empty($date)) {
                        // Check date format
                        if (!preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $date, $match)) {
                            $isError = true;
                            Hpj_CMLabs_Notice::addError('Date is invalid');    
                        }
                    } else {
                        $isError = true;
                        Hpj_CMLabs_Notice::addError('Date is required');    
                    }
                }                                        
                if (!$isError) {
                    $downloadModel = new Hpj_CMLabs_Download_Model();
                    if (!empty($id)) {
                        if ($downloadModel->updateDownload( $fileName, $fileUrl, $platform, $size, $description, $requirement, $applicationId, $published, $date, $id )) {
                            $isOk = true;
                            Hpj_CMLabs_Form::cleanFormData($url);
                            Hpj_CMLabs_Notice::addMessage('Download updated');    
                        } else {
                            Hpj_CMLabs_Notice::addError('Updating failed');    
                        }    
                    } else {
                        if ($downloadModel->insertDownload( $fileName, $fileUrl, $platform, $size, $description, $requirement, $applicationId, $published )) {
                            $isOk = true;
                            Hpj_CMLabs_Form::cleanFormData($url);
                            Hpj_CMLabs_Notice::addMessage('Download added');    
                        } else {
                            Hpj_CMLabs_Notice::addError('Saving failed');    
                        }
                    }
                }
            }
            if ($isEdit && !$isOk) {
                wp_redirect( $url . '&mode=edit&id=' . (int)$id);        
            } else {
                wp_redirect( $url );
            }                                                                     
            exit;    
        }
    }
    
    public function deleteDownloadAction() {
        if ((!empty($_GET['page']) && $_GET['page'] == 'hpj-cmlabs-downloads') && (!empty($_GET['action']) && $_GET['action'] == 'download') && (!empty($_GET['mode']) && $_GET['mode'] == 'delete') && (!empty($_GET['id']) && (int)$_GET['id'])) {
            $id = (!empty( $_GET['id'] )) ? $_GET['id'] : null;
            $downloadModel = new Hpj_CMLabs_Download_Model();
            if ($downloadModel->deleteDownload($id)) {
                Hpj_CMLabs_Notice::addMessage('Download deleted');   
            } else {
                Hpj_CMLabs_Notice::addError('Deleting failed');    
            }                            
            wp_redirect( admin_url(HPJ_CMLABS_ADMIN_URL_DOWNLOAD));
            exit;        
        }
    }
    
    public function applicationsAction() {
        $applicationModel = new Hpj_CMLabs_Application_Model();
        $applications = $applicationModel->getApplicationFromDB();
        
        $form_datas = Hpj_CMLabs_Form::getFormData(admin_url(HPJ_CMLABS_ADMIN_URL_APPLICATION));
        if (empty($form_datas) && !empty($_GET['mode']) && $_GET['mode'] = 'edit' && !empty($_GET['id']) && (int)$_GET['id']) {
            $application = $applicationModel->getApplicationByIdFromDB((int)$_GET['id']);
            if (!empty($application)) {
                $form_datas['hpj_cmlabs_setting_application_id'] = $application->id;
                $form_datas['hpj_cmlabs_setting_application_name'] = $application->name;
				$form_datas['hpj_cmlabs_setting_application_category'] = $application->category;
                $form_datas['hpj_cmlabs_setting_application_version'] = $application->version;
                $form_datas['hpj_cmlabs_setting_application_edition'] = $application->edition;
				$form_datas['hpj_cmlabs_setting_application_displayorder'] = $application->displayorder;
                $form_datas['hpj_cmlabs_setting_application_published'] = $application->published;          
            }
        }
        
        $datas = array(
            'applications' => $applications,
            'form_datas' => $form_datas
        );
        return array(
            'view' => 'admin/setting/applications.php',
            'data' => $datas
        );    
    }
    
    public function applicationsSaveAction() {
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'hpj_cmlabs_setting_application_save' ) {
            if (is_user_logged_in()) {
                $isEdit = false;
                $isOk = false;
                $url = admin_url(HPJ_CMLABS_ADMIN_URL_APPLICATION);
                Hpj_CMLabs_Form::addFormData($url, $_POST);
				
                $id = (!empty( $_POST['hpj_cmlabs_setting_application_id'] )) ? $_POST['hpj_cmlabs_setting_application_id'] : null;
                $name = (!empty( $_POST['hpj_cmlabs_setting_application_name'] )) ? $_POST['hpj_cmlabs_setting_application_name'] : null;
				$category = (!empty( $_POST['hpj_cmlabs_setting_application_category'] )) ? $_POST['hpj_cmlabs_setting_application_category'] : null;
                $version = (!empty( $_POST['hpj_cmlabs_setting_application_version'] )) ? $_POST['hpj_cmlabs_setting_application_version'] : '';
                $edition = (!empty( $_POST['hpj_cmlabs_setting_application_edition'] )) ? $_POST['hpj_cmlabs_setting_application_edition'] : null;
				$displayorder = (!empty( $_POST['hpj_cmlabs_setting_application_displayorder'] )) ? $_POST['hpj_cmlabs_setting_application_displayorder'] : 0;
                $published = (!empty( $_POST['hpj_cmlabs_setting_application_published'] )) ? $_POST['hpj_cmlabs_setting_application_published'] : 0;
                
                if (!empty($id)) {
                    $isEdit = true;
                }
                                                                    
                $isError = false;
                if (empty($name) || trim($name) == '') {
                    $isError = true;
                    Hpj_CMLabs_Notice::addError('Name is required');    
                }
                /*if (empty($version) || trim($version) == '') {
                    $isError = true;
                    Hpj_CMLabs_Notice::addError('Version is required');    
                }*/
                if (empty($edition) || trim($edition) == '') {
                    $isError = true;
                    Hpj_CMLabs_Notice::addError('Edition is required');    
                }
                if (!$isError) {
                    $applicationModel = new Hpj_CMLabs_Application_Model();
                                        
                    $application = $applicationModel->getApplicationByNameFromDB($name, $version);
                    if (!empty($application) && !$isEdit) {
                        $isError = true;
                        Hpj_CMLabs_Notice::addError('Application already exist');    
                    } else {
                        if (!empty($id)) {
                            if ($applicationId = $applicationModel->updateApplication($name, $version, $edition, $published, $category, $displayorder, $id)) {
                                $isOk = true;
                                Hpj_CMLabs_Form::cleanFormData($url);
                                Hpj_CMLabs_Notice::addMessage('Application updated');    
                            } else {
                                Hpj_CMLabs_Notice::addError('Updating failed');    
                            }    
                        } else {
                            if ($applicationId = $applicationModel->insertApplication($name, $version, $edition, $published, $category, $displayorder)) {
                                $isOk = true;
                                Hpj_CMLabs_Form::cleanFormData($url);
                                Hpj_CMLabs_Notice::addMessage('Application added');    
                            } else {
                                Hpj_CMLabs_Notice::addError('Saving failed');    
                            }   
                        }
                    }          
                }
            }
            if ($isEdit && !$isOk) {
                wp_redirect( $url . '&mode=edit&id=' . (int)$id);        
            } else {
                wp_redirect( $url );
            }                                                                     
            exit;    
        }
    }
    
    public function deleteApplicationAction() {
        if ((!empty($_GET['page']) && $_GET['page'] == 'hpj-cmlabs-downloads') && (!empty($_GET['action']) && $_GET['action'] == 'application') && (!empty($_GET['mode']) && $_GET['mode'] == 'delete') && (!empty($_GET['id']) && (int)$_GET['id'])) {
            $id = (!empty( $_GET['id'] )) ? $_GET['id'] : null;
            $applicationModel = new Hpj_CMLabs_Application_Model();
            if ($applicationModel->deleteApplication($id)) {
                Hpj_CMLabs_Notice::addMessage('Application deleted');   
            } else {
                Hpj_CMLabs_Notice::addError('Deleting failed');    
            }                            
            wp_redirect( admin_url(HPJ_CMLABS_ADMIN_URL_APPLICATION));
            exit;        
        }
    }
    
}
