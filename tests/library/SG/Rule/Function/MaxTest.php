<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_Function_MaxTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test the operators
     */
    public function testMax()
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
        $max = new SG_Rule_Function_Max($collection);
        $this->assertEquals(0, $max->getResult($variables));
        
        $collection = array($var0, $var25, $var50, $var75, $var100);
        $max = new SG_Rule_Function_Max($collection);
        $this->assertEquals(100, $max->getResult($variables));
        
        $max1 = new SG_Rule_Function_Max(array($var0, $var50));
        $max2 = new SG_Rule_Function_Max(array($var50, $var100));
        $max  = new SG_Rule_Function_Max(array(
            $max1, $max2, $var50
        ));
        $this->assertEquals(100, $max->getResult($variables));
    }
    
    /**
     * Test the to string functionality 
     */
    public function testToString()
    {
        $collection = array(
            new SG_Rule_Param(100),
            new SG_Rule_Param(90),
            new SG_Rule_Param(80),
            new SG_Rule_Param(70),
        );
        
        $function = new SG_Rule_Function_Max($collection);
        
        $this->assertEquals('MAX(100;90;80;70)', (string)$function);
    }
}