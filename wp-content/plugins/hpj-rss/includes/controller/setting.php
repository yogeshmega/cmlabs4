<?php
defined( 'ABSPATH' ) or die( 'No direct access!' );

include_once(__DIR__ . '/controller.php');        

class Hpj_RSS_Setting_Controller extends Hpj_RSS_Controller {

    public function generalAction() {
        $pages = get_pages('array');
        $posts = get_posts(array('numberposts' => ''));
        $datas = array(
            'pages' => $pages,
            'posts' => $posts
        );
        return array(
            'view' => 'admin/setting/general.php',
            'data' => $datas
        );
    }
}
