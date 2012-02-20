<?php

class IndexControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{

    public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
    }

    public function testIndexAction()
    {
        $params = array('action' => 'index', 'controller' => 'Index', 'module' => 'default');
        $urlParams = $this->urlizeOptions($params);
        $url = $this->url($urlParams);
        $this->dispatch($url);
        
        // assertions
        $this->assertModule($urlParams['module']);
        $this->assertController($urlParams['controller']);
        $this->assertAction($urlParams['action']);
        $this->assertQueryContentContains("div#welcome h3", "This is your project's main page");
    }

    /**
     * Helper to login as an administrator (has access to all controllers)
     * 
     * @param void
     * 
     * @return User_Model_Row_User
     *     The admin user object
     */
    public function loginAdmin()
    {
        $admin = $this->_userMapper->find(1)->current();
        $this->loginUser($admin);
        return $admin;
    }
    
    /**
     * Helper to login as an specific user
     * 
     * @param User_Model_Row_User
     * 
     * @return User_Model_Row_User
     */
    public function loginUser(User_Model_Row_User $user)
    {
        $auth = User_Model_Auth();
        $auth->authenticateUser($user);
    }
}



