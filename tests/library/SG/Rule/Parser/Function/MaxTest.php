<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_Parser_Function_MaxTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test the parser 
     */
    public function testParse()
    {
        $patterns = new SG_Rule_Parser_Patterns('FOO');
        $parser = new SG_Rule_Parser_Function_Max();
        
        $result = $parser->parse('MAX(30;40;50)', $patterns);
        $this->assertInstanceOf('SG_Rule_Function_Max', $result);
    }
    
    /**
     * Test the exceptions
     * 
     * @todo: finish the test
     */
    public function testParserExceptions()
    {
        $parser = new SG_Rule_Parser_Function_Max();
        $patterns = new SG_Rule_Parser_Patterns('FOO');
        
        try {
            $result = $parser->parse('AND(MAX(30;40;50))', $patterns);
            $this->fail('The string is wrongly parsed');
        }
        catch(Exception $e) {
            $this->assertInstanceOf('SG_Rule_Parser_Exception', $e);
        }
        
        try {
            $result = $parser->parse('MAX(30;40);50', $patterns);
            $this->fail('The string is wrongly parsed');
        }
        catch(Exception $e) {
            $this->assertInstanceOf('SG_Rule_Parser_Exception', $e);
        }
        
        try {
            $result = $parser->parse('MAX 30;40;50)', $patterns);
            $this->fail('The string is wrongly parsed');
        }
        catch(Exception $e) {
            $this->assertInstanceOf('SG_Rule_Parser_Exception', $e);
        }
    }
}


