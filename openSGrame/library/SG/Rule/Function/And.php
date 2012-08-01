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
 * SG_Rule_Function_And
 * 
 * Checks if every param or formula is true
 * 
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Rule_Function_And extends SG_Rule_Function_Collection
{
    /**
     * Function prefix
     * 
     * @var array 
     */
    const FUNCTION_NAME = 'AND';
    
    /**
     * Get the value
     * 
     * @param SG_Rule_Variables
     * 
     * @return mixed
     */
    public function getResult(SG_Rule_Variables $variables) 
    {
        $values = $this->_getCollectionValues($variables);
        
        if(count($values) === 0) {
            return FALSE;
        }
        
        foreach($values AS $value) {
          if(!(bool)$value) {
            return FALSE;
          }
        }
        
        return TRUE;
    }
}

