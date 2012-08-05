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
     * Parse a string
     * 
     * @param string $string
     * @param string $type
     *     (optional) parse by specific type
     * 
     * @return mixed
     */
    public function parse($string, $type = null)
    {
        // remove spaces and newlines
        $string = preg_replace('/\s/', null, $string);
        
        if (!$type) {
            $info = $this->match($string);
            $type = $info['token'];
        }
        
        $parserName = 'SG_Rule_Parser_' . $type;
        if (!class_exists($parserName)) {
            throw new SG_Rule_Parser_Exception('Unknown pattern type');
        }
        
        $parser = new $parserName();
        return $parser->parse($string, $this);
    }
    
    /**
     * Match a given string with the known patterns
     * 
     * @param  string $source
     * 
     * @return array
     * 
     * @throws SG_Rule_Parser_Exception 
     */
    public function match($string) {
        $result = $this->_match($string);
        if($result === false) {
            throw new SG_Rule_Parser_Exception('Unable to parse string.');
        }
        return $result;
    }
    
    /**
     * The actual matching functionality
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
    
    /**
     * Split a string in parts defined by a given string
     * 
     * Delimters between () are excluded
     * 
     * @param string|array $patterns
     * @param string $string
     * @param string|array $excludes
     *     (optional) string or array of strings that should excluded from 
     *     the split
     * 
     * @return array
     */
    static function split($patterns, $string, $excludes = array())
    {
        if (!is_array($patterns)) {
            $patterns = array($patterns);
        }
        if ($excludes && !is_array($excludes)) {
            $excludes = array($excludes);
        }
        
        $stringLength = strlen($string);
        $parts  = array();
        $part   = NULL;
        $level  = 0;
        for($i = 0; $i < $stringLength; $i++) {
            $sub = substr($string, $i, 1);
      
            if ($sub === '(') {
                $part .= $sub;
                $level++;
                continue;
            }
            if ($sub === ')') {
                $part .= $sub;
                $level--;
                continue;
            }
            if ($level > 0) {
                $part .= $sub;
                continue;
            }
            if ($excludes) {
                $match = FALSE;
                foreach($excludes AS $exclude) {
                    $test = substr($string, $i, strlen($exclude));
                    if ($test !== $exclude) {
                        continue;
                    }
                    $match = $test;
                    break;
                }
                if ($match) {
                    $part .= $match;
                    $i = $i + strlen($match) - 1;
                    continue;
                }
            }
            
            $found = false;
            foreach($patterns AS $pattern) {
                $patternLength = strlen($pattern);
                if (substr($string, $i, $patternLength) === $pattern) {
                    $parts[] = $part;
                    $part = NULL;
                    $i = $i + ($patternLength - 1);
                    $found = true;
                    continue;
                }
            }
            if ($found) {
                continue;
            }

            $part .= $sub;
        }
        $parts[] = $part;
        
        return $parts;
    }
}