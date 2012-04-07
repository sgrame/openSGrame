<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_ParamVariableTest extends PHPUnit_Framework_TestCase
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
        
        $variable = new SG_Rule_ParamVariable('test');
        $this->assertNull($variable->getValue($variables));
        
        $variable = new SG_Rule_ParamVariable('bar');
        $this->assertEquals($test['bar'], $variable->getValue($variables));
        
        $this->assertInstanceOf(
            'SG_Rule_ParamVariable',
            $variable->setKey('foo')
        );
        $this->assertEquals($test['foo'], $variable->getValue($variables));
    }
}