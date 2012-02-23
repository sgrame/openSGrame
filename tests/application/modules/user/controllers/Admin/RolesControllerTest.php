<?php

class User_Admin_RolesControllerTest extends SG_Test_PHPUnit_ControllerTestCase
{

    public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
    }

    public function testIndexAction()
    {
        $params = array('action' => 'index', 'controller' => 'roles', 'module' => 'user');
        $urlParams = $this->urlizeOptions($params);
        $url = $this->url($urlParams, 'admin');
        
        // not logged in
        $this->dispatch($url);
        
        // assertions
        $this->assertModule('default');
        $this->assertController('error');
        $this->assertAction('error');
        $this->assertResponseCode(403);
        
        // logged in
        $acl = $this->setUpAcl(array('user:admin:roles' => array('view')));
        $this->resetResponse()->resetRequest();
        $this->dispatch($url);
        
        // assertions
        $this->assertModule($urlParams['module']);
        $this->assertController('admin_' . $urlParams['controller']);
        $this->assertAction($urlParams['action']);
        /*$this->assertQueryContentContains(
            'div.page-header h1',
            'Manage roles'
        );*/
    }


}



