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
 * SG_Rule_Formula_Average
 * 
 * Calculates the average out of an array of params or formulas
 * 
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Rule_Formula_Average extends SG_Rule_Formula_Collection
{
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
        
        $count = count($values);
        if($count === 0) {
            return 0;
        }
        
        return array_sum($values) / count($values);
    }
}

