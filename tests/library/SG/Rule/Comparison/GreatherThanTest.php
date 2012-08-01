<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_Comparison_GreatherThanTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test Getters & Setters
     */
    public function testGetSetters()
    {
        $variables = new SG_Rule_Variables(array('foo' => 70, 'bar' => 30));
        $varFoo  = new SG_Rule_Param_Variable('foo');
        $varBar  = new SG_Rule_Param_Variable('bar');
        $param30 = new SG_Rule_Param(30);
        $param70 = new SG_Rule_Param(70);
      
        // setters & getters
        $comparison = new SG_Rule_Comparison_GreatherThan(
            $param70, 
            $param30
        );
        
        // getters
        $this->assertEquals($param70, $comparison->getLeft());
        $this->assertEquals($param30, $comparison->getRight());
        
        // setters
        $this->assertInstanceOf(
            'SG_Rule_Comparison_Abstract', 
            $comparison->setLeft($varFoo)
        );
        $this->assertEquals($varFoo, $comparison->getLeft());
        $this->assertInstanceOf(
            'SG_Rule_Comparison_Abstract', 
            $comparison->setRight($varBar)
        );
        $this->assertEquals($varBar, $comparison->getRight());
    }

    /**
     * Test the operators
     */
    public function testGreatherThan()
    {
        $variables = new SG_Rule_Variables(array('foo' => 70, 'bar' => 30));
        $varFoo  = new SG_Rule_Param_Variable('foo');
        $varBar  = new SG_Rule_Param_Variable('bar');
        $param30 = new SG_Rule_Param(30);
        $param70 = new SG_Rule_Param(70);
        
        $comparison = new SG_Rule_Comparison_GreatherThan(
            $param70, 
            $param30
        );
        $this->assertTrue($comparison->getResult($variables));
        $comparison->setRight($param70);
        $this->assertFalse($comparison->getResult($variables));
    }
    
    /**
     * Test the to string method 
     */
    public function testToString()
    {
        $left  = new SG_Rule_Param(100);
        $right = new SG_Rule_Param(50);
        $comparison = new SG_Rule_Comparison_GreatherThan($left, $right);
        
        $this->assertEquals('100>50', (string)$comparison);
    }
}