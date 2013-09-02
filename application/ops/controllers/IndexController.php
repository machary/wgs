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
        if(isset($identity->kogas) AND $this->checkOpsKogas($identity->kogas))
        {
            $this->_redirector->gotoSimple('index', strtolower($identity->kogas));
        }
	}

	public function settingTaktisAction()
	{
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        if(isset($identity->kogas) AND $this->checkOpsKogas($identity->kogas))
        {
            $this->_redirector->gotoSimple('index', strtolower($identity->kogas));
        }
	}
}