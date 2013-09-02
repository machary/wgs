<?php
/**
 * Helper Full Url
 */
class Zend_View_Helper_FullUrl extends Zend_View_Helper_Abstract
{
    private $_base;

    public function __construct()
    {
        $this->_base = ROOT_URL . Zend_Controller_Front::getInstance()->getBaseUrl();
    }

    public function FullUrl($str = '')
    {
        if ($str && strpos($str, '/') !== 0) $str = '/'.$str;
        return $this->_base.$str;
    }
}