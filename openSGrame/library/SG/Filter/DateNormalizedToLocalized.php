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
 * SG_Filter_DateNormalizedToLocalized
 *
 * Changes a date from normalized format to localized version.
 *
 * @category SG
 * @package  Filter
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Filter_DateNormalizedToLocalized 
    extends Zend_Filter_NormalizedToLocalized
{
    /**
     * Defined by Zend_Filter_Interface
     *
     * @param  string $value
     * @return string
     */
    public function filter($_value)
    {   
        // check if isset
        if(!$_value) {
            return null;
        }
        
        // check if valid date
        $validate = new Zend_Validate_Date('yyyy-MM-dd');
        if(!$validate->isValid($_value)) {
            return $_value;
        }
        
        $date = new Zend_Date($_value, 'yyyy-MM-dd');
        return $date->toString('dd/MM/yyyy');
    }
}