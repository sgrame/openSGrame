<?php

class User_PasswordControllerTest extends SG_Test_PHPUnit_ControllerTestCase
{

    public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
    }

    public function testIndexAction()
    {
        $params = array('action' => 'index', 'controller' => 'password', 'module' => 'user');
        $urlParams = $this->urlizeOptions($params);
        $url = $this->url($urlParams);
        
        // not logged in
        $this->dispatch($url);
        
        // assertions
        $this->assertModule('default');
        $this->assertController('error');
        $this->assertAction('error');
        
        // assert error
        $this->assertResponseCode(403);
        
        $acl = $this->setUpAcl(array('user:password' => array('view')));
        // Acl allowed
        $this->resetResponse()->resetRequest();
        $this->dispatch($url);
        $this->assertResponseCode(200);
        
        // assertions
        $this->assertModule($params['module']);
        $this->assertController($params['controller']);
        $this->assertAction($params['action']);
    }


}



