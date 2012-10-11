<?php
/**
 * @group SG
 * @group SG_Validate
 * @group SG_Validate_Rule
 */
class SG_Validate_RuleTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test get/set parser
     */
    public function testGetSetParser()
    {
        $parser = new SG_Rule_Parser_Rule();
        
        $validator = new SG_Validate_Rule();
        $this->assertInstanceOf('SG_Validate_Rule', $validator->setParser($parser));
        $this->assertEquals($parser, $validator->getParser());
    }
    
    /**
     * Test get/set parser
     */
    public function testGetSetPatterns()
    {
        $patterns = new SG_Rule_Parser_Patterns('FOO');
        
        $validator = new SG_Validate_Rule();
        $this->assertInstanceOf('SG_Validate_Rule', $validator->setPatterns($patterns));
        $this->assertEquals($patterns, $validator->getPatterns());
    }
    
    /**
     * Test with simple param
     */
    public function testValidate()
    {
        $parser = new SG_Rule_Parser_Rule();
        $patterns = new SG_Rule_Parser_Patterns('FOO');
        
        $validator = new SG_Validate_Rule();
        $validator->setParser($parser);
        $validator->setPatterns($patterns);
        
        $this->assertTrue($validator->isValid('100>10'));
        $this->assertTrue($validator->isValid('FOO10>10'));
        $this->assertFalse($validator->isValid('BAR10>10'));
        $this->assertFalse($validator->isValid('AND(10>100'));
        $this->assertEquals(
            SG_Validate_Rule::INVALID_RULE, 
            current($validator->getErrors())
        );
        $this->assertEquals(
            "'AND(10>100' is not a valid rule",
            current($validator->getMessages())
        );
    }
    
    
}