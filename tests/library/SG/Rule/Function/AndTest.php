<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_Function_AndTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test the operators
     */
    public function testAnd()
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
        $and = new SG_Rule_Function_And($collection);
        $this->assertFalse($and->getResult($variables));
        
        $collection = array($var0, $var25, $var50, $var75, $var100);
        $and = new SG_Rule_Function_And($collection);
        $this->assertFalse($and->getResult($variables));
        
        $and1 = new SG_Rule_Function_And(array($var25, $var50));
        $and2 = new SG_Rule_Function_And(array($var50, $var100));
        $and  = new SG_Rule_Function_And(array(
            $and1, $and2, $var50
        ));
        $this->assertTrue($and->getResult($variables));
    }
}