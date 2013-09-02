<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    // Stores a copy of the config object in the Registry for future references
    // !IMPORTANT: Must be run before any other inits
    protected function _initConfig()
    {
        Zend_Registry::set('config', new Zend_Config($this->getOptions()));
    }

    // Initializes the default timezone for the php ENV
    protected function _initDate()
    {
        date_default_timezone_set(Zend_Registry::get('config')->settings->application->datetime);
    }

    // Stores a copy of all the database adapters in the Registry for future references
    protected function _initDatabases()
    {
        $this->bootstrap('multidb');
        $resource = $this->getPluginResource('multidb');
        $databases = Zend_Registry::get('config')->resources->multidb;
        foreach ($databases as $name => $adapter)
        {
            $db_adapter = $resource->getDb($name);
            Zend_Registry::set($name, $db_adapter);
        }
    }

    protected function _initView()
    {
        // Initialize view
        $view = new Zend_View();
        $view->doctype('XHTML1_STRICT');
        $view->headTitle()->setSeparator(' - ');
        // Add it to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'ViewRenderer'
        );
        $viewRenderer->setView($view);
        // Return it, so that it can be stored by the bootstrap
        return $view;
    }

    protected function _initAutoload()
    {
        $moduleLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath'  => APPLICATION_PATH));

        return $moduleLoader;
    }


}

