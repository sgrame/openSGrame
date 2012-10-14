<?php
/**
 * @group SG_Modules
 * @group SG_Modules_Activity
 */
class Activity_Model_Activity_ActionTest 
    extends SG_Test_PHPUnit_ControllerTestCase
{
    /**
     * Setup the framework, create the sql database 
     */
    public function setUp()
    {
        $this->bootstrap = new Zend_Application(
            APPLICATION_ENV, 
            APPLICATION_PATH . '/configs/application.ini'
        );
        parent::setUp();
    }
    
    /**
     * Test the constructor
     */
    public function testConstructor()
    {
        $action = new Activity_Model_Activity_Action();
        $this->assertNull($action->getText());
        $this->assertNull($action->getModule());
        $this->assertNull($action->getController());
        $this->assertNull($action->getAction());
        $this->assertEquals(
            array(
                'module'     => NULL, 
                'controller' => NULL,
                'action'     => NULL,
            ), 
            $action->getUrlOptions()
        );
        $this->assertNull($action->getRouterName());
        
        $action = new Activity_Model_Activity_Action(
            'Action test text',
            'module',
            'controller',
            'action',
            array('param' => 'value'),
            'routerName'
        );
        $test = array(
            'module'     => 'module',
            'controller' => 'controller',
            'action'     => 'action',
            'param'      => 'value',
        );
        $this->assertEquals('Action test text', $action->getText());
        $this->assertEquals($test, $action->getUrlOptions());
        $this->assertEquals('routerName', $action->getRouterName());
    }
    
    /**
     * Test the get-set text methods
     */
    public function testGetSetText()
    {
        $action = new Activity_Model_Activity_Action();
        $this->assertNull($action->getText());
        
        $text = 'TEST TITLE';
        $this->assertInstanceOf(
            'Activity_Model_Activity_Action', 
            $action->setText($text)
        );
        $this->assertEquals($text, $action->getText());
    }
    
    /**
     * Test the get-set module methods
     */
    public function testGetSetModule()
    {
        $action = new Activity_Model_Activity_Action();
        $this->assertNull($action->getModule());
        
        $module = 'TestModule';
        $this->assertInstanceOf(
            'Activity_Model_Activity_Action', 
            $action->setModule($module)
        );
        $this->assertEquals($module, $action->getModule());
    }
    
    /**
     * Test the get-set controller methods
     */
    public function testGetSetController()
    {
        $action = new Activity_Model_Activity_Action();
        $this->assertNull($action->getController());
        
        $test = 'TestController';
        $this->assertInstanceOf(
            'Activity_Model_Activity_Action', 
            $action->setController($test)
        );
        $this->assertEquals($test, $action->getController());
    }
    
    /**
     * Test the get-set action methods
     */
    public function testGetSetAction()
    {
        $action = new Activity_Model_Activity_Action();
        $this->assertNull($action->getAction());
        
        $actionName = 'TestAction';
        $this->assertInstanceOf(
            'Activity_Model_Activity_Action', 
            $action->setAction($actionName)
        );
        $this->assertEquals($actionName, $action->getAction());
    }
    
    /**
     * Test the param setter
     */
    public function testSetParam()
    {
        $action = new Activity_Model_Activity_Action();
        
        $key = 'test';
        $val = 'value';
        $this->assertInstanceOf(
            'Activity_Model_Activity_Action', 
            $action->setParam($key, $val)
        );
        $all = $action->getUrlOptions();
        $this->assertArrayHasKey($key, $all);
        $this->assertEquals($val, $all[$key]);
    }
    
    /**
     * Test setting multiple params
     */
    public function testSetParams()
    {
        $action = new Activity_Model_Activity_Action();
        
        $test = array('param1' => 'val1', 'param2' => 'val2');
        $this->assertInstanceOf(
            'Activity_Model_Activity_Action', 
            $action->setParams($test)
        );
        $all = $action->getUrlOptions();
        foreach($test AS $key => $value) {
            $this->assertEquals($value, $all[$key]);
        }
    }
    
    /**
     * Test getUrlOptions
     */
    public function getUrlOptions()
    {
        $action = new Activity_Model_Activity_Action();
        $action->setModule('module');
        $action->setController('controller');
        $action->setAction('action');
        $action->setParam('param', 'value');
        
        $test = array(
            'module'     => 'module',
            'controller' => 'controller',
            'action'     => 'action',
            'param'      => 'value',
        );
        $this->assertEquals($test, $action->getUrlOptions());
    }
    
    /**
     * Test the get-set router name methods
     */
    public function testGetSetRouterName()
    {
        $action = new Activity_Model_Activity_Action();
        $this->assertNull($action->getRouterName());
        
        $test = 'TestRouter';
        $this->assertInstanceOf(
            'Activity_Model_Activity_Action', 
            $action->setRouterName($test)
        );
        $this->assertEquals($test, $action->getRouterName());
    }
}
