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
     * The parser
     * 
     * @param string $string
     * @param SG_Rule_Parser_Patterns $patterns
     * 
     * @return SG_Rule 
     */
    public function parse($string, SG_Rule_Parser_Patterns $patterns)
    {
        return new SG_Rule($patterns->parse($string));
    }
}