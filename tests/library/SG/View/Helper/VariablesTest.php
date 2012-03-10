<?php
/**
 * @group SG
 */
class SG_View_Helper_VariablesTest extends SG_Test_PHPUnit_ControllerTestCase
{
    /**
     * Loads the bootstrap
     */
    public function setUp()
    {
        $this->bootstrap = new Zend_Application(
            APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini'
        );
        parent::setUp();
    }   
  
    /**
     * Test direct
     */
    public function testVariables()
    {
        $vars = SG_Variables::getInstance();
        $helper = new SG_View_Helper_Variables();
        
        $this->assertEquals(
            $vars->get('site_name'),
            $helper->variables('site_name')
        );
        
        $nonexistingkey = 'nonexistingvariablekeytotestthis';
        $this->assertEquals(
            'default value',
            $helper->variables($nonexistingkey, 'default value')
        );
        
        $this->assertNull($helper->variables($nonexistingkey));
    }
    
    /**
     * Test get
     */
    public function testGet()
    {
        $vars = SG_Variables::getInstance();
        $helper = new SG_View_Helper_Variables();
        
        $this->assertEquals(
            $vars->get('site_name'),
            $helper->get('site_name')
        );
        
        $this->assertEquals(
            'default value',
            $helper->get('nonexistingvariablekeytotestthis', 'default value')
        );
    }
    
    /**
     * Test exists
     */
    public function testExists()
    {
        $helper = new SG_View_Helper_Variables();
        
        $this->assertTrue($helper->exists('site_name'));
        
        $this->assertFalse(
            $helper->exists('nonexistingvariablekeytotestthis')
        );
    }
}