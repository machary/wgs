<?php
/**
 * parent controller untuk semua error controller
 * @author tajhul.faijin@sangkuriang.co.id
 */
class App_Error extends Zend_Controller_Action
{
    public function init(){

        //hanya mengecek environtment
        if( APPLICATION_ENV || !is_null(APPLICATION_ENV) ) {
            switch( APPLICATION_ENV ) {
                case 'development' :
                    $errors = $this->_getParam('error_handler');

                    if (!$errors || !$errors instanceof ArrayObject) {
                        $this->view->message = 'You have reached the error page';
                        return;
                    }

                    switch ($errors->type) {
                        case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
                        case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
                        case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                            // 404 error -- controller or action not found
                            $this->getResponse()->setHttpResponseCode(404);
                            $priority = Zend_Log::NOTICE;
                            $this->view->message = 'Page not found';
                            break;
                        default:
                            // application error
                            $this->getResponse()->setHttpResponseCode(500);
                            $priority = Zend_Log::CRIT;
                            $this->view->message = 'Application error';
                            break;
                    }

                    // Log exception, if logger available
                    if ($log = $this->getLog()) {
                        $log->log($this->view->message, $priority, $errors->exception);
                        $log->log('Request Parameters', $priority, $errors->request->getParams());
                    }

                    // conditionally display exceptions
                    if ($this->getInvokeArg('displayExceptions') == true) {
                        $this->view->exception = $errors->exception;
                    }
                    $this->view->request   = $errors->request;
                    $this->view->displayErrors  = true;
                break;

                case 'production' :
                    $this->view->displayErrors  = false;
                break;
            }

        }
    }

    protected function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }
}