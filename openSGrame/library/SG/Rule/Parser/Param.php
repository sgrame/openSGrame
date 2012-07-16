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
 * SG_Rule_Parser_Param
 *
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Rule_Parser_Param extends SG_Rule_Parser_Abstract
{
    /**
     * Parse a param string
     * 
     * @param string $string
     * @param SG_Rule_Parser_Patterns $patterns
     * 
     * @return array
     * 
     * @throws SG_Rule_Parser_Exception 
     */
    public function parse($string, SG_Rule_Parser_Patterns $patterns) {
        $info   = $patterns->match($string);
        
        if (!isset($info['token']) 
            || $info['token'] !== SG_Rule_Parser_Patterns::PARAM
        ) {
            throw new SG_Rule_Parser_Exception('Unable to parse string.');
        }
        
        return new SG_Rule_Param($string);
    }
}