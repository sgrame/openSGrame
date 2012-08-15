<?php
/**
 * @category SG
 * @package  Filter
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Filter_Sortable
 *
 * Filters a sortable input value to an array
 * The sortable input element returns a serialized version of the sortable
 * array. This is in the format field[]=1&field[]=2&field[]=3
 * 
 * This filter parses that string to an array
 *
 * @category SG
 * @package  Filter
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Filter_Sortable implements Zend_Filter_Interface 
{
    /**
     * Defined by Zend_Filter_Interface
     *
     * Returns the array value
     *
     * @param  string $value
     * @return array
     */
    public function filter($value)
    {
        if(empty($value)) {
            return array();
        }
        
        $result = parse_str($value, $output);
        return $output;
    }
}
