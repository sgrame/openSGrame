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
 * SG_Rule_Parser_Rule
 *
 * Parser to parse a string representation of a rule to the object version of it
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
class SG_Rule_Parser_Rule extends SG_Rule_Parser_Abstract
{
    /**
     * Possible variable prefixes
     * 
     * @var array 
     */
    protected $_prefixes = array();
    
    /**
     * The pattern library
     * 
     * @var array 
     */
    protected $_patterns;
    
    
    /**
     * Constructor
     * 
     * @param string|array $prefixes
     *     (optional) the prefixes in use for the variables 
     * @param object $patterns
     *     (optional) the pattern library to use
     */
    public function __construct($prefixes = array(), $patterns = NULL)
    {
        if (!is_array($prefixes)) {
            $prefixes = array($prefixes);
        }
        
        // add the prefixes to the patterns
        foreach($prefixes AS $prefix) {
            if (!$prefix) {
                continue;
            }
            
            $this->_prefixes[] = $prefix;
        }
        
        if (!$patterns) {
            $patterns = new SG_Rule_Parser_Pattern($this->_prefixes);
        }
        $this->_patterns = $patterns;
    }
    
    
    /**
     * The parser
     * 
     * @param string $string
     * 
     * @return SG_Rule 
     */
    public function parse($string)
    {
        return new SG_Rule($string);
    }
}