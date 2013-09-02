<?php
class Peta_ProxyController extends Zend_Controller_Action
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        //parent::disableView();
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $uri = urldecode($this->_getParam('url'));
        $client = new Zend_Http_Client($uri, array(
            'Accept-encoding' => 'gzip,deflate',
            'maxredirects' => 0,
            'timeout'      => 30));
        //$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        //$client->setRawData($xml)->setEncType('text/xml')->request('POST');
        $response = $client->request();
        if($response->isSuccessful())
        {
            echo $response->getBody();
        }
    }

}