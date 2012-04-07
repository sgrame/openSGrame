<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_VariablesTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test constructor
     */
    public function testVariables()
    {
        $variables = new SG_Rule_Variables();
        $this->assertTrue(is_array($variables->getVariables()));
        $this->assertEmpty($variables->getVariables());
        
        $test = array('foo' => 1, 'bar' => '2');
        $variables = new SG_Rule_Variables($test);
        $this->assertEquals($test, $variables->getVariables());
        
        $variables = new SG_Rule_Variables();
        $this->assertEmpty($variables->getVariables());
        $this->assertInstanceOf(
            'SG_Rule_Variables', 
            $variables->setVariables($test)
        );
        $this->assertEquals($test, $variables->getVariables());
    }
    
    /**
     * Test value
     */
    public function testValue()
    {
        $test = array('foo' => 1, 'bar' => '2');
        $variables = new SG_Rule_Variables($test);
        
        $this->assertEquals($test['foo'], $variables->getValue('foo'));
        
        $this->assertNull($variables->getValue('buz'));
        $this->assertEquals('baz', $variables->getValue('buz', 'baz'));
        
        $this->assertInstanceOf(
            'SG_Rule_Variables',
            $variables->addVariable('buz', 'baz')
        );
        $this->assertEquals('baz', $variables->getValue('buz'));
        
        $this->assertInstanceOf(
            'SG_Rule_Variables',
            $variables->setValue('buz', 'biz')
        );
        $this->assertEquals('biz', $variables->getValue('buz'));
    }
}