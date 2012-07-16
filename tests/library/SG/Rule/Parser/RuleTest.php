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
        $parser = new SG_Rule_Parser_Rule('FOO');
        $this->assertInstanceOf('SG_Rule', $parser->parse('FOO123=123'));
    }
}