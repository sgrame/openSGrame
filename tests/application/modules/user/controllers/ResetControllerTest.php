<?php
/**
 * @group user
 * @group controller
 */
class User_ResetControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{

    public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
    }

    public function testResetAction()
    {
        $params = array('action' => 'index', 'controller' => 'Reset', 'module' => 'user');
        $urlParams = $this->urlizeOptions($params);
        $url = $this->url($urlParams);
        $this->dispatch($url);
        
        // assertions
        $this->assertModule($urlParams['module']);
        $this->assertController($urlParams['controller']);
        $this->assertAction($urlParams['action']);
        $this->assertQueryContentContains(
            '#fieldset-reset legend',
            'Reset password'
        );
    }


}



