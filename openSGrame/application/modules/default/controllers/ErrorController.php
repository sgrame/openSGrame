<?php

/**
 * Default Error controller
 */
class Default_ErrorController extends Zend_Controller_Action
{
    /**
     * Set the default layout for error handler
     */
    public function init() {
        $this->_helper->layout->setLayout('layout-well');
    }
    
    /**
     * Error action
     * 
     * Catches all uncatched Exceptions
     */
    public function errorAction()
    {
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
                SG_Log::log($errors->exception->getMessage(), SG_Log::WARNING);
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $priority = Zend_Log::CRIT;
                $this->view->message = 'Application error';
                SG_Log::log($errors->exception->getMessage(), SG_Log::CRITICAL);
                break;
        }
        
        // check for specific exceptions
        switch(get_class($errors->exception)) {
            case 'SG_Controller_Action_NotAuthorized_Exception':
                $this->getResponse()->setHttpResponseCode(403);
                $priority = Zend_Log::NOTICE;
                $this->view->message = 'No access';
                SG_Log::log($errors->exception->getMessage(), SG_Log::WARNING);
                break;
            case 'SG_Controller_Action_NotFound_Exception':
                $this->getResponse()->setHttpResponseCode(404);
                $priority = Zend_Log::NOTICE;
                $this->view->message = 'Page not found';
                SG_Log::log($errors->exception->getMessage(), SG_Log::WARNING);
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
        
        $this->view->request = $errors->request;
    }

    /**
     * Gets the log from the config (if any)
     * 
     * @param  void
     * @return Zend_Log
     */
    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }
}

