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
 * SG_Rule_Parser_Interface
 *
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
interface SG_Rule_Parser_Interface 
{
    /**
     * Parse the string, return the SG_Rule object
     * 
     * @param string $string
     *     The string to parse
     * @param SG_Rule_Parser_Patterns $patterns
     *     The patterns collection to use in the parser
     * 
     * @return mixed 
     */
    public function parse($string, SG_Rule_Parser_Patterns $patterns);
}