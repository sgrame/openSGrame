<?php
/**
 * @group SG
 * @group SG_Rule
 * @group SG_Rule_Parser
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
            $parser->parse('FOO123=100', $patterns)
        );
    }
    
    /**
     * Full blown parser 
     * 
     * @group SG_Rule_ParserFormula
     */
    public function testParserFormula()
    {
        $formula = <<<EOT
AND(
    AVG(FOO10;FOO11;FOO12;FOO13;FOO14;FOO15) >= 40,
    FOO10 >= 70,
    AVG(FOO10;FOO18) >= 70,
    FOO10 <= AVG(FOO13;FOO18)
)     
EOT;
        
        $patterns = new SG_Rule_Parser_Patterns('FOO');
        $parser = new SG_Rule_Parser_Rule();
        
        $rule = $parser->parse($formula, $patterns);
        $this->assertInstanceOf('SG_Rule', $rule);
    }
    
    /**
     * Test specific strings 
     */
    public function testExamples()
    {
        $patterns = new SG_Rule_Parser_Patterns('CT-');
        $parser   = new SG_Rule_Parser_Rule();
        
        $formula  = 'AND(CT-1>=70;CT-2>=70;CT-3>=70)';
        $rule = $parser->parse($formula, $patterns);
        $this->assertInstanceOf('SG_Rule', $rule);
    }
}