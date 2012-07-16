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
 * SG_Rule_Parser_Comparison_Equal
 *
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Rule_Parser_Comparison_Equal extends SG_Rule_Parser_Abstract
{
    /**
     * Parse a equal string
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
            || $info['token'] !== SG_Rule_Parser_Patterns::COMPARISON_EQUAL
        ) {
            throw new SG_Rule_Parser_Exception('Unable to parse string.');
        }
        
        // split into parts
        $string = str_replace(' ', NULL, $string);
        $parts = preg_split('/=/', $string);
        $left  = $parts[0];
        $right = $parts[1];
        
        return TRUE;
        return new SG_Rule_Comparison_Equal($left, $right);
    }
}