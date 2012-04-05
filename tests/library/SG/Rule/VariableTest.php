<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_VariableTest extends PHPUnit_Framework_TestCase
{
    /**
     * setUp
     */
    public function setUp()
    {
        
    }
    
    /**
     * Teardown
     */
    public function tearDown()
    {
        
    }
  
    /**
     * Test constructor
     */
    public function testVariable()
    {
        $test = array('foo' => 1, 'bar' => '2');
        $variables = new SG_Rule_Variables($test);
        
        $variable = new SG_Rule_Variable('foo');
        $this->assertNull($variable->getVariables());
        $this->assertNull($variable->getValue());
        
        $this->assertInstanceOf(
            'SG_Rule_Variable',
            $variable->setVariables($variables)
        );
        $this->assertEquals($variables, $variable->getVariables());
        $this->assertEquals($test['foo'], $variable->getValue());
        
        
        $variable = new SG_Rule_Variable('bar', $variables);
        $this->assertEquals($variables, $variable->getVariables());
        $this->assertEquals($test['bar'], $variable->getValue());
        
        $this->assertInstanceOf(
            'SG_Rule_Variable',
            $variable->setKey('foo')
        );
        $this->assertEquals($test['foo'], $variable->getValue());
    }
}