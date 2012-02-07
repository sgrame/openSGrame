<?php

class User_LogoutController extends Zend_Controller_Action
{
    /**
     * Redirect after login
     * 
     * @var string
     */
    protected $_goto = '/user/login';
    
    /**
     * The authentication model
     * 
     * @var User_Model_Auth
     */
    protected $_model;
    
    /**
     * The messenger
     * 
     * @var TB_Controller_Action_Helper_Messenger
     */
    protected $_messenger;
    
    
    /**
     * Initiate the controller
     */
    public function init()
    {
        $this->_messenger = $this->_helper->getHelper('Messenger');
        $this->_model = new User_Model_Auth();
    }

    /**
     * Log the user out
     */
    public function indexAction()
    {
        $this->_model->unsetAuth();
        // we are logged in
        $this->_messenger->addInfo(
            '<strong>You are now logged out</strong>'
        );
        $this->_redirect($this->_goto);
    }
}

