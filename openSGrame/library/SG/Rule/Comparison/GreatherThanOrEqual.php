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
 * SG_Rule_Comparison_GreatherThanOrEqual
 * 
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Rule_Comparison_GreatherThanOrEqual
    extends SG_Rule_Comparison_Abstract
{
    /**
     * Comparison operator
     * 
     * @var string 
     */
    const OPERATOR = '>=';
    
    /**
     * Compare the 2 values
     * 
     * @param mixed
     * @param mixed
     * 
     * @return bool
     */
    protected function _compare($left, $right)
    {
        return ($left >= $right);
    }
}

