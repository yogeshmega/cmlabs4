<?php

defined( 'ABSPATH' ) or die( 'No direct access!' );
                   
class Hpj_CMLabs_Url {

    private static $pageUrls = array();
    
    public static function init() {
        self::$pageUrls = array(
            //HPJ_CMLABS_PAGE_PROFILE => HPJ_CMLABS_URL_PROFILE,
			HPJ_CMLABS_PAGE_ACCOUNT_WELCOME => HPJ_CMLABS_URL_ACCOUNT_WELCOME,
            HPJ_CMLABS_PAGE_ACCOUNT_PROFILE => HPJ_CMLABS_URL_ACCOUNT_PROFILE,
            HPJ_CMLABS_PAGE_LICENSES => HPJ_CMLABS_URL_LICENSES,
            HPJ_CMLABS_PAGE_LICENSES_ACTIVATION => HPJ_CMLABS_URL_LICENSES_ACTIVATION,
            HPJ_CMLABS_PAGE_LICENSES_DOWNLOAD => HPJ_CMLABS_URL_LICENSES_DOWNLOAD,
            //HPJ_CMLABS_PAGE_LICENSES_GENERATE => HPJ_CMLABS_URL_LICENSES_GENERATE,
            HPJ_CMLABS_PAGE_DOWNLOADS => HPJ_CMLABS_URL_DOWNLOADS,
            HPJ_CMLABS_PAGE_DOCUMENTATION => HPJ_CMLABS_URL_DOCUMENTATION,
            HPJ_CMLABS_PAGE_CONTACT_SALE => HPJ_CMLABS_URL_CONTACT_SALE,
            HPJ_CMLABS_PAGE_CONTACT_SUPPORT => HPJ_CMLABS_URL_CONTACT_SUPPORT,
            HPJ_CMLABS_PAGE_CONTACT_RENEW => HPJ_CMLABS_URL_CONTACT_RENEW,
            HPJ_CMLABS_PAGE_CONTACT_LICENSING => HPJ_CMLABS_URL_CONTACT_LICENSING,
			HPJ_CMLABS_PAGE_ACTIVATE_LICENSE => HPJ_CMLABS_URL_ACTIVATE_LICENSE,
			HPJ_CMLABS_PAGE_SALES => HPJ_CMLABS_URL_SALES
        );                                                 
    }
    
    public static function getRightUrl($url) {
        if (!empty($url) && trim($url) != '') {
            if (!empty(self::$pageUrls)) {
                foreach (self::$pageUrls as $pageId => $pageUrl) {
                    if ($url == $pageId || in_array($url, explode('|', $pageUrl))) {
                        return $pageId;
                    }
                }
            }
        }
        return null;
    }
    
    public static function getUrlByPageIdAndLang($pageId, $lang) {
        if (!empty($pageId) && trim($pageId) != '' && !empty($lang) && trim($lang) != '') {
            if (!empty(self::$pageUrls) && !empty(self::$pageUrls[$pageId])) {
                $pages = explode('|', self::$pageUrls[$pageId]);
                foreach ($pages as $p) {
                    if ($lang != 'en' && preg_match('/' . $lang . '\/[a-zA-Z0-9_-]+/', $p, $match )) {
                        return '/' . $p;    
                    } elseif ( preg_match('/[a-zA-Z0-9_-]+/', $p, $match ) ) {
						return '/' . $p;   
					}  
                }           
            }    
        }
        return null;      
    }
    
    public static function getUrlByPageId($pageId) {
        if (!empty($pageId) && trim($pageId)) {
            $lang = get_bloginfo("language");
            if (!empty($lang) && strlen(trim($lang)) > 2) {
                $lang = substr($lang, 0, 2);    
            }
            return self::getUrlByPageIdAndLang($pageId, $lang);   
        }    
    }
}                      

Hpj_CMLabs_Url::init(); 
?>