<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_Comparison_LessThanTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test the operators
     */
    public function testLessThan()
    {
        $variables = new SG_Rule_Variables(array('foo' => 70, 'bar' => 30));
        $varFoo  = new SG_Rule_Param_Variable('foo');
        $varBar  = new SG_Rule_Param_Variable('bar');
        $param30 = new SG_Rule_Param(30);
        $param70 = new SG_Rule_Param(70);
        
        $comparison = new SG_Rule_Comparison_LessThan(
            $param30, 
            $param70
        );
        $this->assertTrue($comparison->getResult($variables));
        $comparison->setRight($param30);
        $this->assertFalse($comparison->getResult($variables));
    }
    
    /**
     * Test the to string method 
     */
    public function testToString()
    {
        $left  = new SG_Rule_Param(100);
        $right = new SG_Rule_Param(50);
        $comparison = new SG_Rule_Comparison_LessThan($left, $right);
        
        $this->assertEquals('100<50', (string)$comparison);
    }
}