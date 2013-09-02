<?php
/**
 * Helper JavaScript Url
 */
class Zend_View_Helper_JsUrl extends Zend_View_Helper_Abstract 
{
    private $_base;
    
    public function __construct() 
    {
        $this->_base = Zend_Controller_Front::getInstance()->getBaseUrl() . '/js/';
    }
    
    public function jsUrl($str = '') 
    {
        return $this->_base . $str;
    }
}