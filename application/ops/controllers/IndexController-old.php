<?php
/**
 * List of Kogas
 * 
 * @author Kanwil
 */
 
class Ops_IndexController extends App_Controller
{
	public function indexAction()
	{
        $identity = Zend_Auth::getInstance()->getStorage()->read();
	
        if(isset($identity->kogas))
        {
            $this->_redirector->gotoSimple('index', $identity->kogas);
        }
	}
}