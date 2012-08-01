<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_Comparison_EqualTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test the operators
     */
    public function testEqual()
    {
        $variables = new SG_Rule_Variables(array('foo' => 70, 'bar' => 30));
        $varFoo  = new SG_Rule_Param_Variable('foo');
        $varBar  = new SG_Rule_Param_Variable('bar');
        $param30 = new SG_Rule_Param(30);
        $param70 = new SG_Rule_Param(70);
        
        $comparison = new SG_Rule_Comparison_Equal(
            $param30, 
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
        $comparison = new SG_Rule_Comparison_Equal($left, $right);
        
        $this->assertEquals('100=50', (string)$comparison);
    }
}