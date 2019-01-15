<?php
defined( 'ABSPATH' ) or die( 'No direct access!' );

include_once(__DIR__ . '/model.php');                        
include_once(__DIR__ . '/../library/rlm/product.php');       

class Hpj_CMLabs_Product_Model extends Hpj_CMLabs_Model {

    public function getProductFromRLM() {
        $products = array();
        $productClass = Hpj_CMLabs_RLM_Product::getInstance();
        $products = $productClass->get();
        return $products;
    }
    
    public function getProductByNameFromRLM($productName) {
        $product = null;
        if (!empty($productName) && trim($productName) != '') {
            $productClass = Hpj_CMLabs_RLM_Product::getInstance();
            $product = $productClass->getProductByName($productName);    
        }
        return $product;    
    }
    
}