<?php

class Default_ErrorControllerTest extends SG_Test_PHPUnit_ControllerTestCase
{

    public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
    }

    public function testErrorAction()
    {
        $params = array('action' => 'error', 'controller' => 'error', 'module' => 'default');
        $urlParams = $this->urlizeOptions($params);
        
        $url = $this->url($urlParams);
        $this->dispatch($url);
        
        // assertions
        $this->assertModule($urlParams['module']);
        $this->assertController($urlParams['controller']);
        $this->assertAction($urlParams['action']);
        $this->assertQueryContentContains(
            'h1',
            'An error occurred'
        );
    }

}



