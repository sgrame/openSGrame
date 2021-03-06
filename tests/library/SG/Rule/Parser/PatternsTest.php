<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_Parser_PatternsTest extends PHPUnit_Framework_TestCase
{
    /**
     * Array with lexicon tests 
     * 
     * It contains arrays with 3 params:
     *   - the string to parse
     *   - the expected match
     *   - the found token type
     * 
     * @var array
     */
    protected $_strings = array(
        // Functions
        array('AND(CT1; CT2; CT3)', 'AND(CT1; CT2; CT3)', SG_Rule_Parser_Patterns::FUNCTION_AND),
        array('AND(AVG(CT1; CT2; CT3); CT4)', 'AND(AVG(CT1; CT2; CT3); CT4)', SG_Rule_Parser_Patterns::FUNCTION_AND),
        array('AND(AVG(CT1; CT2; CT3); CT4)', 'AND(AVG(CT1; CT2; CT3); CT4)', SG_Rule_Parser_Patterns::FUNCTION_AND),
        array('AVG(CT1; CT2; CT3)', 'AVG(CT1; CT2; CT3)', SG_Rule_Parser_Patterns::FUNCTION_AVERAGE),
        array('MAX(CT1; CT2; CT3)', 'MAX(CT1; CT2; CT3)', SG_Rule_Parser_Patterns::FUNCTION_MAX),
        array('MIN(CT1; CT2; CT3)', 'MIN(CT1; CT2; CT3)', SG_Rule_Parser_Patterns::FUNCTION_MIN),
        array('OR(CT1; CT2; CT3)', 'OR(CT1; CT2; CT3)', SG_Rule_Parser_Patterns::FUNCTION_OR),
        
        // Comparison
        array('CT1 = 40',  'CT1 = 40',  SG_Rule_Parser_Patterns::COMPARISON_EQUAL),
        array('CT1=40',    'CT1=40',    SG_Rule_Parser_Patterns::COMPARISON_EQUAL),
        array('CT1 > 40',  'CT1 > 40',  SG_Rule_Parser_Patterns::COMPARISON_GREATHER_THAN),
        array('CT1>40',    'CT1>40',    SG_Rule_Parser_Patterns::COMPARISON_GREATHER_THAN),
        array('CT1 >= 40', 'CT1 >= 40', SG_Rule_Parser_Patterns::COMPARISON_GREATHER_THAN_OR_EQUAL),
        array('CT1>=40',   'CT1>=40',   SG_Rule_Parser_Patterns::COMPARISON_GREATHER_THAN_OR_EQUAL),
        array('CT1 < 40',  'CT1 < 40',  SG_Rule_Parser_Patterns::COMPARISON_LESS_THAN),
        array('CT1<40',    'CT1<40',    SG_Rule_Parser_Patterns::COMPARISON_LESS_THAN),
        array('CT1 <= 40', 'CT1 <= 40', SG_Rule_Parser_Patterns::COMPARISON_LESS_THAN_OR_EQUAL),
        array('CT1<=40',   'CT1<=40',   SG_Rule_Parser_Patterns::COMPARISON_LESS_THAN_OR_EQUAL),
        array('CT1 != 40', 'CT1 != 40', SG_Rule_Parser_Patterns::COMPARISON_NOT_EQUAL),
        array('CT1!=40',   'CT1!=40',   SG_Rule_Parser_Patterns::COMPARISON_NOT_EQUAL),
        
        // Variable
        array('CT0',    'CT0',    SG_Rule_Parser_Patterns::VARIABLE),
        array('CT1',    'CT1',    SG_Rule_Parser_Patterns::VARIABLE),
        array('CT12',   'CT12',   SG_Rule_Parser_Patterns::VARIABLE),
        array('CT123',  'CT123',  SG_Rule_Parser_Patterns::VARIABLE),
        array('CT1234', 'CT1234', SG_Rule_Parser_Patterns::VARIABLE),
        
        // Values
        array( 0,        0,       SG_Rule_Parser_Patterns::PARAM),
        array('1',      '1',      SG_Rule_Parser_Patterns::PARAM),
        array( 1,        1,       SG_Rule_Parser_Patterns::PARAM),
        array('12',     '12',     SG_Rule_Parser_Patterns::PARAM),
        array( 12,       12,      SG_Rule_Parser_Patterns::PARAM),
        array('100',    '100',    SG_Rule_Parser_Patterns::PARAM),
        array( 100,      100,     SG_Rule_Parser_Patterns::PARAM),
    );
    
    /**
     * Failing patterns
     * 
     * @var array
     */
    protected $_stringsFail = array(
        'AND(CT1; CT2; CT3',
        'AND AVG(CT1; CT2; CT3); CT4)',
        'MINUS(CT1; CT2; CT3)',
        'OR CT1; CT2; CT3',
        
        // Variable
        'CT0',
        'CT1',
        'CT12',
        'CT123',
        'CT1234',
        
        // Value
        101,
        -2,
        '101',
        '-2',
    );
    
    
    
    /**
     * Test the parser 
     */
    public function testParse()
    {
        $patterns = new SG_Rule_Parser_Patterns('FOO');
        $result = $patterns->parse(15);
        $this->assertInstanceOf('SG_Rule_Param', $result);
        
        $result = $patterns->parse('FOO15');
        $this->assertInstanceOf('SG_Rule_Param_Variable', $result);
    }
    
    
    /**
     * Test the matching 
     */
    public function testMatch()
    {
        $patterns = new SG_Rule_Parser_Patterns('CT');
        
        foreach ($this->_strings AS $params) {
            $result = $patterns->match($params[0]);
            $this->assertEquals($params[1], $result['match']);
            $this->assertEquals($params[2], $result['token']);
            
            //var_dump($result);
        }
        
    }
    
    /**
     * Test the exceptions 
     */
    public function testMatchExceptions()
    {
        $patterns = new SG_Rule_Parser_Patterns('FOO');
        
        foreach ($this->_stringsFail AS $test) {
            try {
                $patterns->match($test);
            }
            catch(Exception $e) {
                $this->assertInstanceOf('SG_Rule_Parser_Exception', $e);
                continue;
            }
            $this->fail('String "' . $test . '" should not pass');
        }
    }
    
    /**
     * Test the split 
     */
    public function testSplit()
    {
        $result = SG_Rule_Parser_Patterns::split('=', 'A=B');
        $this->assertEquals(array('A', 'B'), $result);
        
        $result = SG_Rule_Parser_Patterns::split('=', 'A=B;(C=D)');
        $this->assertEquals(array('A', 'B;(C=D)'), $result);
        
        $result = SG_Rule_Parser_Patterns::split('=', 'A!=B', '!=');
        $this->assertEquals(array('A!=B'), $result);
        
        $result = SG_Rule_Parser_Patterns::split('=', 'A!=B', array('!='));
        $this->assertEquals(array('A!=B'), $result);
        
        
        $result = SG_Rule_Parser_Patterns::split('!=', 'A!=B');
        $this->assertEquals(array('A', 'B'), $result);
        
        
        $result = SG_Rule_Parser_Patterns::split(';', 'A;B;C;D');
        $this->assertEquals(array('A', 'B', 'C', 'D'), $result);
        
        $result = SG_Rule_Parser_Patterns::split('>', 'A>B');
        $this->assertEquals(array('A', 'B'), $result);
        
        $result = SG_Rule_Parser_Patterns::split('>', 'A>B;A>=B', '>=');
        $this->assertEquals(array('A', 'B;A>=B'), $result);
        
        
        $result = SG_Rule_Parser_Patterns::split('>=', 'A>=B');
        $this->assertEquals(array('A', 'B'), $result);
        
        $result = SG_Rule_Parser_Patterns::split('<', 'A<B');
        $this->assertEquals(array('A', 'B'), $result);
        
        $result = SG_Rule_Parser_Patterns::split('<', 'A<B;A<=B', '<=');
        $this->assertEquals(array('A', 'B;A<=B'), $result);
        
        $result = SG_Rule_Parser_Patterns::split('<=', 'A<=B');
        $this->assertEquals(array('A', 'B'), $result);
        
        $result = SG_Rule_Parser_Patterns::split('=', 'A=B;A!=B', '!=');
        $this->assertEquals(array('A', 'B;A!=B'), $result);
        
    }
}