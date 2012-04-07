<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_ParamTest extends PHPUnit_Framework_TestCase
{
    /**
     * setUp
     */
    public function setUp()
    {
        
    }
    
    /**
     * Teardown
     */
    public function tearDown()
    {
        
    }
  
    /**
     * Test constructor
     */
    public function testValue()
    {
        $value = new SG_Rule_Param();
        $this->assertNull($value->getValue());
        
        $test = 123456;
        $value = new SG_Rule_Param($test);
        $this->assertEquals($test, $value->getValue());
        
        $test2 = 654321;
        $value->setValue($test2);
        $this->assertEquals($test2, $value->getValue());
    }
}