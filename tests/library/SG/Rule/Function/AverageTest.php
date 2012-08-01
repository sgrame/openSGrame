<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_Function_AverageTest extends PHPUnit_Framework_TestCase
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
        
        $collection = array($varFoo, $varBar);
        $average = new SG_Rule_Function_Average($collection);
        $this->assertEquals($collection, $average->getCollection());
        
        $collection = array($param30, $param70);
        $this->assertInstanceOf(
            'SG_Rule_Function_Average',
            $average->setCollection($collection)
        );
        $this->assertEquals($collection, $average->getCollection());
        
        $this->assertInstanceOf(
            'SG_Rule_Function_Average',
            $average->addItem($varFoo)
        );
        $collection = $average->getCollection();
        $this->assertEquals($varFoo, array_pop($collection));
    }

    /**
     * Test the operators
     */
    public function testAverage()
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
        $average = new SG_Rule_Function_Average($collection);
        $this->assertEquals(0, $average->getResult($variables));
        
        $collection = array($var0, $var25, $var50, $var75, $var100);
        $average = new SG_Rule_Function_Average($collection);
        $this->assertEquals(50, $average->getResult($variables));
        
        $average1 = new SG_Rule_Function_Average(array($var0, $var50));
        $average2 = new SG_Rule_Function_Average(array($var50, $var100));
        $average  = new SG_Rule_Function_Average(array(
            $average1, $average2, $var50
        ));
        $this->assertEquals(50, $average->getResult($variables));
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
        
        $function = new SG_Rule_Function_Average($collection);
        
        $this->assertEquals('AVG(100;90;80;70)', (string)$function);
    }
}