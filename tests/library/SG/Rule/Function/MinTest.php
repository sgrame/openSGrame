<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_Function_MinTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test the operators
     */
    public function testMin()
    {
        $variables = new SG_Rule_Variables(array(
            'baz' =>  0,
            'bez' => 25,
            'biz' => 50,
            'boz' => 75,
            'buz' => 100
        ));
        $var0   = new SG_Rule_Param_Variable('baz');
        $var25  = new SG_Rule_Param_Variable('bez');
        $var50  = new SG_Rule_Param_Variable('biz');
        $var75  = new SG_Rule_Param_Variable('boz');
        $var100 = new SG_Rule_Param_Variable('buz');
        
        $collection = array();
        $min = new SG_Rule_Function_Min($collection);
        $this->assertEquals(0, $min->getResult($variables));
        
        $collection = array($var0, $var25, $var50, $var75, $var100);
        $min = new SG_Rule_Function_Min($collection);
        $this->assertTrue(0 === $min->getResult($variables));
        
        $min1 = new SG_Rule_Function_Min(array($var25, $var50));
        $min2 = new SG_Rule_Function_Min(array($var50, $var100));
        $min  = new SG_Rule_Function_Min(array(
            $min1, $min2, $var50
        ));
        $this->assertEquals(25, $min->getResult($variables));
    }
}