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
 * SG_Filter_DateLocalizedToNormalized
 *
 * Filters a localized date format to the normalized version
 *
 * @category SG
 * @package  Filter
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Filter_DateLocalizedToNormalized 
    extends Zend_Filter_LocalizedToNormalized
{
    /**
     * Defined by Zend_Filter_Interface
     *
     * @param  string $value
     * @return string
     */
    public function filter($_value)
    {   
        // check if value
        if(!$_value) {
            return null;
        }
        
        // check if given date is an existing date
        if (Zend_Locale_Format::checkDateFormat($_value, $this->_options)) {
            // Detect date or time input
            $date = Zend_Locale_Format::getDate($_value, $this->_options);
            return $date['year'] . '-' . $date['month'] . '-' . $date['day'];
        }

        return $_value;
    }
}