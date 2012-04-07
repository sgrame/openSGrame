<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_Param_ComparionTest extends PHPUnit_Framework_TestCase
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
        $comparison = new SG_Rule_Formula_Comparison(
            $param70, 
            SG_Rule_Formula_Comparison::EQUAL, 
            $param30
        );
        
        // getters
        $this->assertEquals($param70, $comparison->getLeft());
        $this->assertEquals($param30, $comparison->getRight());
        $this->assertEquals(
            SG_Rule_Formula_Comparison::EQUAL, 
            $comparison->getOperator()
        );
        
        // setters
        $this->assertInstanceOf(
            'SG_Rule_Formula_Comparison', 
            $comparison->setLeft($varFoo)
        );
        $this->assertEquals($varFoo, $comparison->getLeft());
        $this->assertInstanceOf(
            'SG_Rule_Formula_Comparison', 
            $comparison->setRight($varBar)
        );
        $this->assertEquals($varBar, $comparison->getRight());
        $this->assertInstanceOf(
            'SG_Rule_Formula_Comparison', 
            $comparison->setOperator(SG_Rule_Formula_Comparison::NOT_EQUAL)
        );
        $this->assertEquals(
            SG_Rule_Formula_Comparison::NOT_EQUAL, 
            $comparison->getOperator()
        );
    }

    /**
     * Test the operators
     */
    public function testOperators()
    {
        $variables = new SG_Rule_Variables(array('foo' => 70, 'bar' => 30));
        $varFoo  = new SG_Rule_Param_Variable('foo');
        $varBar  = new SG_Rule_Param_Variable('bar');
        $param30 = new SG_Rule_Param(30);
        $param70 = new SG_Rule_Param(70);
        
        // equals
        $comparison = new SG_Rule_Formula_Comparison(
            $param30, 
            SG_Rule_Formula_Comparison::EQUAL, 
            $param30
        );
        $this->assertTrue($comparison->getResult($variables));
        $comparison->setRight($param70);
        $this->assertFalse($comparison->getResult($variables));
        
        // not equals
        $comparison = new SG_Rule_Formula_Comparison(
            $param30, 
            SG_Rule_Formula_Comparison::NOT_EQUAL, 
            $varFoo
        );
        $this->assertTrue($comparison->getResult($variables));
        $comparison->setRight($varBar);
        $this->assertFalse($comparison->getResult($variables));
        
        // Greather
        $comparison = new SG_Rule_Formula_Comparison(
            $param70, 
            SG_Rule_Formula_Comparison::GREATHER, 
            $param30
        );
        $this->assertTrue($comparison->getResult($variables));
        $comparison->setRight($param70);
        $this->assertFalse($comparison->getResult($variables));
        
        // Greather of equal
        $comparison = new SG_Rule_Formula_Comparison(
            $param70, 
            SG_Rule_Formula_Comparison::GREATHER_OR_EQUAL, 
            $param30
        );
        $this->assertTrue($comparison->getResult($variables));
        $comparison->setRight($param70);
        $this->assertTrue($comparison->getResult($variables));
        $comparison->setLeft($param30);
        $this->assertFalse($comparison->getResult($variables));
        
        // Less
        $comparison = new SG_Rule_Formula_Comparison(
            $param30, 
            SG_Rule_Formula_Comparison::LESS, 
            $param70
        );
        $this->assertTrue($comparison->getResult($variables));
        $comparison->setRight($param30);
        $this->assertFalse($comparison->getResult($variables));
        
        // Less or equal
        $comparison = new SG_Rule_Formula_Comparison(
            $param30, 
            SG_Rule_Formula_Comparison::LESS_OR_EQUAL, 
            $param70
        );
        $this->assertTrue($comparison->getResult($variables));
        $comparison->setRight($param30);
        $this->assertTrue($comparison->getResult($variables));
        $comparison->setLeft($param70);
        $this->assertFalse($comparison->getResult($variables));
    }
}