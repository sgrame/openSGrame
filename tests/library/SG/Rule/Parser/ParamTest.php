<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_Parser_ParamTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test the parser 
     */
    public function testParser()
    {
        $parser = new SG_Rule_Parser_Param();
        $patterns = new SG_Rule_Parser_Patterns('FOO');
        
        $result = $parser->parse(35, $patterns);
        $this->assertInstanceOf('SG_Rule_Param', $result);
        
        $result = $parser->parse('99', $patterns);
        $this->assertInstanceOf('SG_Rule_Param', $result);
    }
    
    /**
     * Test the exceptions 
     */
    public function testParserExceptions()
    {
        $parser = new SG_Rule_Parser_Param();
        $patterns = new SG_Rule_Parser_Patterns('FOO');
        
        try {
            $result = $parser->parse('A12', $patterns);
        }
        catch(Exception $e) {
            $this->assertInstanceOf('SG_Rule_Parser_Exception', $e);
            return;
        }
        $this->fail('Test should throw Exception');
    }
}