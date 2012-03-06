<?php

class User_ProfileController extends SG_Controller_Action
{
    /**
     * ACL
     */
    protected $_aclResource  = 'user';
    protected $_aclPrivilege = 'edit profile';

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }


}

