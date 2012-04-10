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
 * SG_Rule_Function_Abstract
 * 
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
abstract class SG_Rule_Function_Abstract
{
    /**
     * Get the value
     * 
     * @param SG_Rule_Variables
     * 
     * @return mixed
     */
    public function getResult(SG_Rule_Variables $variables) 
    {}
    
    /**
     * Helper to get the value out of an item
     * 
     * @param SG_Rule_Param_Abstract|SG_Rule_Function_Abstract $item
     * @param SG_Rule_Variables $variables
     * 
     * @return mixed
     */
    protected function _getItemValue($item, SG_Rule_Variables $variables)
    {
        if($item instanceof SG_Rule_Param_Abstract) {
            return $item->getValue($variables);
        }
        if($item instanceof SG_Rule_Function_Abstract) {
            return $item->getResult($variables);
        }
        
        throw new SG_Rule_Exception('The item is not of a supported type');
    }
}

