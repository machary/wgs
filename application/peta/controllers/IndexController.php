<?php

class Peta_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        //$this->getHelper('viewRenderer')->setNoRender(true);
        //$this->getHelper('layout')->disableLayout();
		
    }

    public function autocompleteAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $model = new Peta_Model_DbTable_GetAutoComplete();
        $data = $model->addAutoComplete();

        foreach($data as $dat)
        {
            array_push($jsonString, $dat);
        }

        echo  json_encode($jsonString);
    }
}

