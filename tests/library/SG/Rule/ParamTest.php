<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_ParamTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test constructor
     */
    public function testValue()
    {
        $variables = new SG_Rule_Variables();
      
        $value = new SG_Rule_Param();
        $this->assertNull($value->getValue($variables));
        
        $test = 123456;
        $value = new SG_Rule_Param($test);
        $this->assertEquals($test, $value->getValue($variables));
        
        $test2 = 654321;
        $this->assertInstanceOf(
          'SG_Rule_Param', 
          $value->setValue($test2)
        );
        $this->assertEquals($test2, $value->getValue($variables));
    }
}