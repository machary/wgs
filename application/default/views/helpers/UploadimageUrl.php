<?php
/**
 * Helper Css Url
 */
class Zend_View_Helper_UploadimageUrl extends Zend_View_Helper_Abstract
{
    private $_base;
    
    public function __construct() 
    {
        $this->_base = Zend_Controller_Front::getInstance()->getBaseUrl() . '/upload/images/';
    }
    
    public function UploadimageUrl($str = '')
    {
        return $this->_base . $str;
    }
}