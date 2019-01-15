<?php
defined( 'ABSPATH' ) or die( 'No direct access!' );

include_once(__DIR__ . '/controller.php');        

class Hpj_RSS_Feed_Controller extends Hpj_RSS_Controller {
        
    private function sortById($a, $b) {
        return $a->ID - $b->ID;    
    }
    
    public function rssAction() {
        $postIds = get_option('hpj_rss_setting_posts_ids');
        /*                                                  
        $pages = array();
        if (!empty($pageIds)) {
            $pages = get_pages(array('include' => $pageIds));
        }
        */
        $posts = array();
        if (!empty($postIds)) {
            $posts = get_posts(array('post__in' => $postIds));
        }
        
        //$posts = array_merge($posts, $pages);
        //usort($posts, array($this, 'sortById'));
                                         
        $datas = array(
            'posts' => $posts
        );
        return array(
            'view' => 'public/feed/rss.php',
            'data' => $datas
        );
    }
    
} 
?>
