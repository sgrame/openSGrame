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
        
        $result = $parser->parse('50=20');
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