<?php

class User_LoginController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_messenger = $this->_helper->getHelper('Messenger');
    }

    public function indexAction()
    {
        $loginForm = new User_Form_Login();
        $loginForm->setAction($this->view->url());
        
        if ($this->_request->isPost()) {
            if ($loginForm->isValid($this->_request->getPost())) {
                $this->_messenger->addSuccess(
                    '<strong>You successfully logged in!</strong>'
                );
            }

            // print error
            else {
                $this->_messenger->addError(
                    '<strong>Please control your username and password</strong>',
                    array('/user/password-reset' => 'Forgot your password?')
                );
            }
        }
        
        $this->view->form = $loginForm;
    }


}

