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
        
        $result = $parser->parse(35);
        $this->assertInstanceOf('SG_Rule_Param', $result);
        
        $result = $parser->parse('99');
        $this->assertInstanceOf('SG_Rule_Param', $result);
    }
    
    /**
     * Test the exceptions 
     */
    public function testParserExceptions()
    {
        $parser = new SG_Rule_Parser_Param();
        
        try {
            $result = $parser->parse('A12');
        }
        catch(Exception $e) {
            $this->assertInstanceOf('SG_Rule_Parser_Exception', $e);
            return;
        }
        $this->fail('Test should throw Exception');
    }
}