<?php

class Default_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $auth = Zend_Auth::getInstance();
        $vars = SG_Variables::getInstance();
        
        // logged in user
        if ($auth->hasIdentity()) {
            $default = $vars->get('default_page_loggedin');
        }
        else {
            $default = $vars->get('default_page_anomynous');
        }
        
        // redirect
        if ($default) {
            $this->_forward(
                $default['action'],
                $default['controller'],
                $default['module'],
                $default['params']
            );
        }
        
        // default this view
    }


}

