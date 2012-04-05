<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_ValueTest extends PHPUnit_Framework_TestCase
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
        $value = new SG_Rule_Value();
        $this->assertNull($value->getValue());
        
        $test = 123456;
        $value = new SG_Rule_Value($test);
        $this->assertEquals($test, $value->getValue());
        
        $test2 = 654321;
        $value->setValue($test2);
        $this->assertEquals($test2, $value->getValue());
    }
}