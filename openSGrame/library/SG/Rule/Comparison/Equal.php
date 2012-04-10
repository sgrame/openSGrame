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
 * SG_Rule_Comparison_Equal
 * 
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Rule_Comparison_Equal
    extends SG_Rule_Comparison_Abstract
{
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
        return ($left == $right);
    }
}

