<?php

class Default_Admin_AdminController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        var_dump($this->getRequest()->getParams());
    }


}
