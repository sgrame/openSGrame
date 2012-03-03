<?php

class Default_Admin_AdminControllerTest extends SG_Test_PHPUnit_ControllerTestCase
{

    public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
    }

    public function testIndexAction()
    {
        $params = array('action' => 'index', 'controller' => 'admin', 'module' => 'default');
        $urlParams = $this->urlizeOptions($params);
        $url = $this->url($urlParams, 'admin');
        
        // not logged in
        $this->dispatch($url);
        $this->assertResponseCode(403);
        
        // logged in
        $acl = $this->setUpAcl(array('system:admin' => array('administer')));
        
        // Acl allowed
        $this->resetRequest();
        $this->resetResponse();
        $this->dispatch($url);
        $this->assertResponseCode(200);
        
        // assertions
        $this->assertModule($urlParams['module']);
        $this->assertController('admin_' . $urlParams['controller']);
        $this->assertAction($urlParams['action']);
        $this->assertQueryContentContains(
            'div#view-content p',
            'View script for controller <b>Admin_Index</b> and script/action name <b>' . $params['action'] . '</b>'
        );
    }


}



