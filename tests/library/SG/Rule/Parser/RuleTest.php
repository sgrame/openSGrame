<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_Parser_RuleTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * Test the parser 
     */
    public function testParser()
    {
        $patterns = new SG_Rule_Parser_Patterns('FOO');
        $parser = new SG_Rule_Parser_Rule();
        $this->assertInstanceOf(
            'SG_Rule', 
            $parser->parse('FOO123=123', $patterns)
        );
    }
}