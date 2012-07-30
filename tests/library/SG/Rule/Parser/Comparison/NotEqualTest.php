<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_Parser_Comparison_NotEqualTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test the splitter
     */
    public function testMatch()
    {
        $patterns = new SG_Rule_Parser_Patterns();
        $parser    = new SG_Rule_Parser_Comparison_NotEqual();
        
        $string = '50!=20';
        $result = $parser->match($string);
        $this->assertEquals(2, count($result));
        $this->assertEquals('50', $result[0]);
        $this->assertEquals('20', $result[1]);
        
        $string = '50!=AND(20=50;30=60;40=70)';
        $result = $parser->match($string);
        $this->assertEquals(2, count($result));
        $this->assertEquals('50', $result[0]);
        $this->assertEquals('AND(20=50;30=60;40=70)', $result[1]);
        
        $string = '50=AND!=A(20=50;30=60;40=70)';
        $result = $parser->match($string);
        $this->assertEquals(2, count($result));
        $this->assertEquals('50=AND', $result[0]);
        $this->assertEquals('A(20=50;30=60;40=70)', $result[1]);
        
        try {
            $string = '50=20';
            $parser->match($string);
            $this->fail('The string is wrongly matched');
        }
        catch(Exception $e) {
            
        }
    }
    
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


