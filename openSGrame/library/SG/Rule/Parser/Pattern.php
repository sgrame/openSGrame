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
class SG_Rule_Parser_Pattern extends SG_Rule_Parser_Abstract
{
    /**
     * Possible variable prefixes
     * 
     * @var array 
     */
    protected $_varPrefixes = array();
    
    /**
     * Lexicon : definition of all parts that the parser can identify
     * 
     * @var array
     */
    protected $_tokens = array(
        // Functions
        '/^(AND\(.+\))$/'    => 'FUNCTION_AND',
        '/^(AVG\(.+\))$/'    => 'FUNCTION_AVERAGE',
        '/^(MAX\(.+\))$/'    => 'FUNCTION_MAX',
        '/^(MIN\(.+\))$/'    => 'FUNCTION_MIN',
        '/^(OR\(.+\))$/'     => 'FUNCTION_OR',
        
        // Comparisons
        '/^(.+!=.+)$/'       => 'COMPARISON_NOT_EQUAL',
        '/^(.+>=.+)$/'       => 'COMPARISON_GREATHER_THAN_OR_EQUAL',
        '/^(.+>.+)$/'        => 'COMPARISON_GREATHER_THAN',
        '/^(.+<=.+)$/'       => 'COMPARISON_LESS_THAN_OR_EQUAL',
        '/^(.+<.+)$/'        => 'COMPARISON_LESS_THAN',
        '/^(.+=.+)$/'        => 'COMPARISON_EQUAL',
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
            
            $this->_tokens['/^(' . $prefix . '[0-9]{1,4})$/'] = 'VARIABLE';
        }
            
        // add the other patterns
        $this->_tokens['/^([0-9]{1,2})$/'] = 'PARAM';
        $this->_tokens['/^(100)$/']        = 'PARAM';
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
        $tokens = array();
        
        $number = 0;
        $offset = 0;
        while($offset < strlen($string)) {
            $result = $this->_match($string, $number, $offset);
            if($result === false) {
                throw new SG_Rule_Parser_Exception('Unable to parse string.');
            }
            $tokens[] = $result;
            $offset += strlen($result['match']);
        }
        
        return $tokens;
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
    protected function _match($string, $number, $offset) {
        $string = substr($string, $offset);

        foreach($this->_tokens as $pattern => $name) {
            if(preg_match($pattern, $string, $matches)) {
                return array(
                    'match' => $matches[1],
                    'token' => $name,
                    'line'  => (int)$number+1
                );
            }
        }

        return false;
    }
}