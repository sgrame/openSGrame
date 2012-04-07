<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_Param_VariableTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test constructor
     */
    public function testVariable()
    {
        $test = array('foo' => 1, 'bar' => '2');
        $variables = new SG_Rule_Variables($test);
        
        $variable = new SG_Rule_Param_Variable('test');
        $this->assertNull($variable->getValue($variables));
        
        $variable = new SG_Rule_Param_Variable('bar');
        $this->assertEquals($test['bar'], $variable->getValue($variables));
        
        $this->assertInstanceOf(
            'SG_Rule_Param_Variable',
            $variable->setKey('foo')
        );
        $this->assertEquals($test['foo'], $variable->getValue($variables));
    }
}