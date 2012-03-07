<?php
/**
 * Filter for Localized to Normalized date formats
 *
 * @filesource
 * @copyright		Serial Graphics Copyright 2010
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			May 17, 2010
 * @version			$Revision: 2 $
 * @modifiedby		$LastChangedBy: SerialGraphics $
 * @lastmodified	$Date: 2012-03-06 23:31:33 +0100 (Tue, 06 Mar 2012) $
 */


 
/**
 * Filters a localized date format to the normailized version
 * 
 */
class SG_Filter_DateLocalizedToNormalized extends Zend_Filter_LocalizedToNormalized
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
    	if(!$_value)
    	{
    		return null;
    	}
    	
        // check if given date is an existing date
    	if (Zend_Locale_Format::checkDateFormat($_value, $this->_options))
    	{
            // Detect date or time input
            $date = Zend_Locale_Format::getDate($_value, $this->_options);
            return $date['year'] . '-' . $date['month'] . '-' . $date['day'];
        }

        return $_value;
    }
}