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
 * SG_Rule_Parser_Comparison_GreatherThanOrEqual
 *
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Rule_Parser_Comparison_GreatherThanOrEqual extends SG_Rule_Parser_Abstract
{
    /**
     * Parse a >= string
     * 
     * @param string $string
     * @param SG_Rule_Parser_Patterns $patterns
     * 
     * @return array
     * 
     * @throws SG_Rule_Parser_Exception 
     */
    public function parse($string, SG_Rule_Parser_Patterns $patterns) {
        $info = $patterns->match($string);
        
        if (!isset($info['token']) 
            || $info['token'] !== SG_Rule_Parser_Patterns::COMPARISON_GREATHER_THAN_OR_EQUAL
        ) {
            throw new SG_Rule_Parser_Exception('Unable to parse string.');
        }
        
        $parts = $patterns->split('>=', $string);
        if (2 !== count($parts)) {
            throw new SG_Rule_Parser_Exception('Unable to parse string.');
        }
        
        $left  = $patterns->parse($parts[0]);
        $right = $patterns->parse($parts[1]);
        
        return new SG_Rule_Comparison_GreatherThanOrEqual($left, $right);
    }
}