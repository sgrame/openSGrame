<?php
/**
 * @group user
 * @group controller
 */
class User_ProfileControllerTest extends SG_Test_PHPUnit_ControllerTestCase
{

    public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
    }

    public function testIndexAction()
    {
        $params = array('action' => 'index', 'controller' => 'profile', 'module' => 'user');
        $urlParams = $this->urlizeOptions($params);
        $url = $this->url($urlParams);
        
        // Not logged in
        $this->dispatch($url);
        $this->assertResponseCode(403);

        // Logged in        
        $acl = $this->setUpAcl(array('user' => array('edit profile')));
        $this->resetResponse()->resetRequest();
        $this->dispatch($url);
        
        // assertions
        $this->assertModule($urlParams['module']);
        $this->assertController($urlParams['controller']);
        $this->assertAction($urlParams['action']);
        $this->assertQueryContentContains(
            'div#view-content p',
            'View script for controller <b>' . ucfirst($params['controller']) . '</b> and script/action name <b>' . $params['action'] . '</b>'
        );
    }


}



