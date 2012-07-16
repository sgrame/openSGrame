<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_Parser_PatternTest extends PHPUnit_Framework_TestCase
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
        array('AND(CT1; CT2; CT3)', 'AND(CT1; CT2; CT3)', SG_Rule_Parser_Pattern::FUNCTION_AND),
        array('AND(AVG(CT1; CT2; CT3); CT4)', 'AND(AVG(CT1; CT2; CT3); CT4)', SG_Rule_Parser_Pattern::FUNCTION_AND),
        array('AND(AVG(CT1; CT2; CT3); CT4)', 'AND(AVG(CT1; CT2; CT3); CT4)', SG_Rule_Parser_Pattern::FUNCTION_AND),
        array('AVG(CT1; CT2; CT3)', 'AVG(CT1; CT2; CT3)', SG_Rule_Parser_Pattern::FUNCTION_AVERAGE),
        array('MAX(CT1; CT2; CT3)', 'MAX(CT1; CT2; CT3)', SG_Rule_Parser_Pattern::FUNCTION_MAX),
        array('MIN(CT1; CT2; CT3)', 'MIN(CT1; CT2; CT3)', SG_Rule_Parser_Pattern::FUNCTION_MIN),
        array('OR(CT1; CT2; CT3)', 'OR(CT1; CT2; CT3)', SG_Rule_Parser_Pattern::FUNCTION_OR),
        
        // Comparison
        array('CT1 = 40',  'CT1 = 40',  SG_Rule_Parser_Pattern::COMPARISON_EQUAL),
        array('CT1=40',    'CT1=40',    SG_Rule_Parser_Pattern::COMPARISON_EQUAL),
        array('CT1 > 40',  'CT1 > 40',  SG_Rule_Parser_Pattern::COMPARISON_GREATHER_THAN),
        array('CT1>40',    'CT1>40',    SG_Rule_Parser_Pattern::COMPARISON_GREATHER_THAN),
        array('CT1 >= 40', 'CT1 >= 40', SG_Rule_Parser_Pattern::COMPARISON_GREATHER_THAN_OR_EQUAL),
        array('CT1>=40',   'CT1>=40',   SG_Rule_Parser_Pattern::COMPARISON_GREATHER_THAN_OR_EQUAL),
        array('CT1 < 40',  'CT1 < 40',  SG_Rule_Parser_Pattern::COMPARISON_LESS_THAN),
        array('CT1<40',    'CT1<40',    SG_Rule_Parser_Pattern::COMPARISON_LESS_THAN),
        array('CT1 <= 40', 'CT1 <= 40', SG_Rule_Parser_Pattern::COMPARISON_LESS_THAN_OR_EQUAL),
        array('CT1<=40',   'CT1<=40',   SG_Rule_Parser_Pattern::COMPARISON_LESS_THAN_OR_EQUAL),
        array('CT1 != 40', 'CT1 != 40', SG_Rule_Parser_Pattern::COMPARISON_NOT_EQUAL),
        array('CT1!=40',   'CT1!=40',   SG_Rule_Parser_Pattern::COMPARISON_NOT_EQUAL),
        
        // Variable
        array('CT0',    'CT0',    SG_Rule_Parser_Pattern::VARIABLE),
        array('CT1',    'CT1',    SG_Rule_Parser_Pattern::VARIABLE),
        array('CT12',   'CT12',   SG_Rule_Parser_Pattern::VARIABLE),
        array('CT123',  'CT123',  SG_Rule_Parser_Pattern::VARIABLE),
        array('CT1234', 'CT1234', SG_Rule_Parser_Pattern::VARIABLE),
        
        // Values
        array( 0,        0,       SG_Rule_Parser_Pattern::PARAM),
        array('1',      '1',      SG_Rule_Parser_Pattern::PARAM),
        array( 1,        1,       SG_Rule_Parser_Pattern::PARAM),
        array('12',     '12',     SG_Rule_Parser_Pattern::PARAM),
        array( 12,       12,      SG_Rule_Parser_Pattern::PARAM),
        array('100',    '100',    SG_Rule_Parser_Pattern::PARAM),
        array( 100,      100,     SG_Rule_Parser_Pattern::PARAM),
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
    public function testParser()
    {
        $parser = new SG_Rule_Parser_Pattern('CT');
        
        foreach ($this->_strings AS $params) {
            $result = $parser->parse($params[0]);
            $this->assertEquals($params[1], $result[0]['match']);
            $this->assertEquals($params[2], $result[0]['token']);
            
            //var_dump($result);
        }
        
    }
    
    /**
     * Test the exceptions 
     */
    public function testParserExceptions()
    {
        $parser = new SG_Rule_Parser_Pattern('FOO');
        
        foreach ($this->_stringsFail AS $test) {
            try {
                $result = $parser->parse($test);
            }
            catch(Exception $e) {
                $this->assertInstanceOf('SG_Rule_Parser_Exception', $e);
                continue;
            }
            $this->fail('String "' . $test . '" should not pass');
        }
    }
}