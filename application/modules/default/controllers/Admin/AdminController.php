<?php

class Default_Admin_AdminController extends SG_Controller_Action
{
    /**
     * ACL
     */
    protected $_aclResource  = 'system:admin';
    protected $_aclPrivilege = 'administer';
    
    
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        var_dump($this->getRequest()->getParams());
    }


}

