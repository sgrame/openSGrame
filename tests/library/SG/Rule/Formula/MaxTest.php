<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_Formula_MaxTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test the operators
     */
    public function testMax()
    {
        $variables = new SG_Rule_Variables(array(
            'bar' =>  0,
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
        $average = new SG_Rule_Formula_Max($collection);
        $this->assertEquals(0, $average->getResult($variables));
        
        $collection = array($var0, $var25, $var50, $var75, $var100);
        $average = new SG_Rule_Formula_Max($collection);
        $this->assertEquals(100, $average->getResult($variables));
        
        $average1 = new SG_Rule_Formula_Max(array($var0, $var50));
        $average2 = new SG_Rule_Formula_Max(array($var50, $var100));
        $average  = new SG_Rule_Formula_Max(array(
            $average1, $average2, $var50
        ));
        $this->assertEquals(100, $average->getResult($variables));
    }
}