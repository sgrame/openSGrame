<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_Parser_Comparison_NotEqualTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test the parser 
     */
    public function testParse()
    {
        $patterns = new SG_Rule_Parser_Patterns('FOO');
        $parser = new SG_Rule_Parser_Comparison_NotEqual();
        
        $result = $parser->parse('50!=20', $patterns);
        $this->assertInstanceOf('SG_Rule_Comparison_NotEqual', $result);
    }
    
    /**
     * Test the exceptions
     * 
     * @todo: finish the test
     */
    public function testParserExceptions()
    {
        $parser = new SG_Rule_Parser_Comparison_NotEqual();
        $patterns = new SG_Rule_Parser_Patterns('FOO');
        
        try {
            $result = $parser->parse('50-60', $patterns);
            $this->fail('The string is wrongly parsed');
        }
        catch(Exception $e) {
            $this->assertInstanceOf('SG_Rule_Parser_Exception', $e);
            return;
        }
    }
}


