<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_Parser_Comparison_EqualTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test the parser 
     */
    public function testParser()
    {
        $parser = new SG_Rule_Parser_Comparison_Equal();
        $patterns = new SG_Rule_Parser_Patterns('FOO');
        
        $result = $parser->parse('50=20', $patterns);
        $this->assertTrue($result);
        
        //$this->assertInstanceOf('Comparison_Equal', $result);
    }
    
    /**
     * Test the exceptions
     * 
     * @todo: finish the test
     */
    public function __testParserExceptions()
    {
        $parser = new SG_Rule_Parser_Param_Variable();
        $patterns = new SG_Rule_Parser_Patterns('FOO');
        
        try {
            $result = $parser->parse('50-60', $patterns);
        }
        catch(Exception $e) {
            $this->assertInstanceOf('SG_Rule_Parser_Exception', $e);
            return;
        }
        $this->fail('Test should throw Exception');
    }
}