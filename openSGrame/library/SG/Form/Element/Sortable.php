<?php
/**
 * @category SG
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Form_Element_Sortable
 *
 * Sortable form element
 * 
 * This element is to be used with sortable lists. Us this element to store the 
 * serialized version of that list.
 * 
 * It has one extra method to get the unserizlized version of the list.
 *
 * @category SG
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Form_Element_Sortable extends Zend_Form_Element_Hidden
{
    /**
     * Get all the lists
     * 
     * @return array 
     */
    public function getLists()
    {
        $filter = new SG_Filter_Sortable();
        return $filter->filter($this->getValue());
    }
    
    /**
     * Get specific list
     * 
     * @param sring $part
     *     the key of the list in the lists array
     * 
     * @return array 
     */
    public function getList($part)
    {
        $lists = $this->getLists();
        
        if (!isset($lists[$part])) {
            return array();
        }
        
        return $lists[$part];
    }
}
