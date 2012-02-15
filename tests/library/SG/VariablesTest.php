<?php

class SG_VariablesTest extends PHPUnit_Framework_TestCase
{
    /**
     * A non existing test variable
     * 
     * @var string
     */
    const VARIABLE_NAME  = 'SG_VariablesTest_VariableName';
    const VARIABLE_VALUE = 'SG_VariablesTest_VariableValue';
    
    /**
     * setUp
     */
    public function setUp()
    {
        $this->_cleanUpVariableTable();
    }
    
    /**
     * Teardown
     */
    public function tearDown()
    {
        $this->_cleanUpVariableTable();
    }
  
    /**
     * Test the singleton
     */
    public function testGetInstance()
    {
        $vars = SG_Variables::getInstance();
        $this->assertInstanceOf('SG_Variables', $vars);
    }
    
    /**
     * Test a not (yet) set variable
     */
    public function testExists()
    {
        $vars = SG_Variables::getInstance();
        $this->assertFalse($vars->exists(self::VARIABLE_NAME));
        $this->assertFalse(isset($vars->{self::VARIABLE_NAME}));
        
        $vars->set(self::VARIABLE_NAME, self::VARIABLE_VALUE);
        
        $this->assertTrue($vars->exists(self::VARIABLE_NAME));
        $this->assertTrue(isset($vars->{self::VARIABLE_NAME}));
    }
    
    /**
     * Test set
     */
    public function testSet()
    {
        $vars = SG_Variables::getInstance();
        
        $vars->set(self::VARIABLE_NAME, self::VARIABLE_VALUE);
        $this->assertEquals(self::VARIABLE_VALUE, $vars->get(self::VARIABLE_NAME));
        $this->assertEquals(self::VARIABLE_VALUE, $vars->{self::VARIABLE_NAME});
        
        $otherValue = 'test 123';
        $vars->{self::VARIABLE_NAME} = $otherValue;
        $this->assertEquals($otherValue, $vars->{self::VARIABLE_NAME});
        
        $otherValue = 'test 321';
        $vars->set(self::VARIABLE_NAME, $otherValue);
        $this->assertEquals($otherValue, $vars->get(self::VARIABLE_NAME));
        
        $otherValue = array(1, 2, 3 => 'TEST 123');
        $vars->set(self::VARIABLE_NAME, $otherValue);
        $this->assertEquals($otherValue, $vars->get(self::VARIABLE_NAME));
    }
    
    /**
     * Test get
     */
    public function testGet()
    {
        $vars = SG_Variables::getInstance();
        
        $nonExistingName = 'SG_VariablesTest_VariableName_NoneExisting';
        $this->assertNull($vars->get($nonExistingName));
        
        $this->assertTrue($vars->get($nonExistingName, true));
    }
    
    
    
    /**
     * Cleanup helper
     */
    protected function _cleanUpVariableTable()
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $stmt = $db->query(
            'DELETE FROM variable WHERE name = ?',
            array(self::VARIABLE_NAME)
        );
        
        $vars = SG_Variables::getInstance()->reload();
    }
}