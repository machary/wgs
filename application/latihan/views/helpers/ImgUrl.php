<?php
/**
 * Helper Image Url
 */
class Zend_View_Helper_ImgUrl extends Zend_View_Helper_Abstract 
{
    private $_base;
    
    public function __construct() 
    {
        $this->_base = Zend_Controller_Front::getInstance()->getBaseUrl() . '/images/';
    }
    
    public function imgUrl($str = '') 
    {
        return $this->_base . $str;
    }
}