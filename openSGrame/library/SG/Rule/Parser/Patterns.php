<?php
/**
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Rule_Parser_Pattern
 *
 * Pattern recognizer
 * 
 * Based on lexer parser article by Michael Nitschinger
 * @see http://nitschinger.at/Writing-a-simple-lexer-in-PHP
 * 
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Rule_Parser_Patterns
{
    /**
     * Pattern names
     * 
     * @var string 
     */
    const FUNCTION_AND                      = 'Function_And';
    const FUNCTION_AVERAGE                  = 'Function_Average';
    const FUNCTION_MAX                      = 'Function_Max';
    const FUNCTION_MIN                      = 'Function_Min';
    const FUNCTION_OR                       = 'Function_OR';
    const COMPARISON_NOT_EQUAL              = 'Comparison_NotEqual';
    const COMPARISON_GREATHER_THAN_OR_EQUAL = 'Comparison_GreatherThanOrEqual';
    const COMPARISON_GREATHER_THAN          = 'Comparison_GreatherThan';
    const COMPARISON_LESS_THAN_OR_EQUAL     = 'Comparison_LessThanOrEqual';
    const COMPARISON_LESS_THAN              = 'Comparison_LessThan';
    const COMPARISON_EQUAL                  = 'Comparison_Equal';
    const VARIABLE                          = 'Param_Variable';
    const PARAM                             = 'Param';
    
    /**
     * Lexicon : definition of all parts that the parser can identify
     * 
     * @var array
     */
    protected $_tokens = array(
        // Functions
        '/^(AND\(.+\))$/'    => self::FUNCTION_AND,
        '/^(AVG\(.+\))$/'    => self::FUNCTION_AVERAGE,
        '/^(MAX\(.+\))$/'    => self::FUNCTION_MAX,
        '/^(MIN\(.+\))$/'    => self::FUNCTION_MIN,
        '/^(OR\(.+\))$/'     => self::FUNCTION_OR,
        
        // Comparisons
        '/^(.+!=.+)$/'       => self::COMPARISON_NOT_EQUAL,
        '/^(.+>=.+)$/'       => self::COMPARISON_GREATHER_THAN_OR_EQUAL,
        '/^(.+>.+)$/'        => self::COMPARISON_GREATHER_THAN,
        '/^(.+<=.+)$/'       => self::COMPARISON_LESS_THAN_OR_EQUAL,
        '/^(.+<.+)$/'        => self::COMPARISON_LESS_THAN,
        '/^(.+=.+)$/'        => self::COMPARISON_EQUAL,
    );
    
    
    /**
     * Constructor
     * 
     * @param string|array $prefixes
     *     (optional) prefixes in use for the variables
     */
    public function __construct($prefixes = array())
    {
        if (!is_array($prefixes)) {
            $prefixes = array($prefixes);
        }
        
        // add the prefixes to the patterns
        foreach($prefixes AS $prefix) {
            if (!$prefix) {
                continue;
            }
            
            $this->_tokens['/^(' . $prefix . '[0-9]{1,4})$/'] = self::VARIABLE;
        }
            
        // add the other patterns
        $this->_tokens['/^([0-9]{1,2})$/'] = self::PARAM;
        $this->_tokens['/^(100)$/']        = self::PARAM;
    }
    
    /**
     * Parse the given string and returns an array with info about the 
     * matching pattern
     * 
     * @param  string $source
     * 
     * @return array
     * 
     * @throws SG_Rule_Parser_Exception 
     */
    public function parse($string) {
        $result = $this->_match($string);
        if($result === false) {
            throw new SG_Rule_Parser_Exception('Unable to parse string.');
        }
        return $result;
    }
    
    /**
     * Loop trough the string and parse the parts
     * 
     * @param string $string
     * @param int $number
     * @param int $offset
     * 
     * @return boolean 
     */
    protected function _match($string) {
        foreach($this->_tokens as $pattern => $name) {
            if(preg_match($pattern, $string, $matches)) {
                return array(
                    'match' => $matches[1],
                    'token' => $name,
                );
            }
        }

        return false;
    }
}