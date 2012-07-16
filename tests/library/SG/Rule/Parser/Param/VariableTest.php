<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_Parser_Param_VariableTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test the parser 
     */
    public function testParser()
    {
        $parser = new SG_Rule_Parser_Param_Variable('FOO');
        
        $result = $parser->parse('FOO15');
        $this->assertInstanceOf('SG_Rule_Param_Variable', $result);
        
        $result = $parser->parse('FOO1');
        $this->assertInstanceOf('SG_Rule_Param_Variable', $result);
    }
    
    /**
     * Test the exceptions 
     */
    public function testParserExceptions()
    {
        $parser = new SG_Rule_Parser_Param_Variable('FOO');
        
        try {
            $result = $parser->parse('BAR12');
        }
        catch(Exception $e) {
            $this->assertInstanceOf('SG_Rule_Parser_Exception', $e);
            return;
        }
        $this->fail('Test should throw Exception');
    }
}