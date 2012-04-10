<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_Formula_OrTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test the operators
     */
    public function testOr()
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
        $or = new SG_Rule_Formula_Or($collection);
        $this->assertFalse($or->getResult($variables));
        
        $collection = array($var0, $var25, $var50, $var75, $var100);
        $or = new SG_Rule_Formula_Or($collection);
        $this->assertTrue($or->getResult($variables));
        
        $or1 = new SG_Rule_Formula_Or(array($var25, $var50));
        $or2 = new SG_Rule_Formula_Or(array($var50, $var100));
        $or  = new SG_Rule_Formula_Or(array(
            $or1, $or2, $var50
        ));
        $this->assertTrue($or->getResult($variables));
        
        $or1 = new SG_Rule_Formula_Or(array($var0, $var0));
        $or2 = new SG_Rule_Formula_Or(array($var0, $var0));
        $or  = new SG_Rule_Formula_Or(array($or1, $or2));
    }
}