<?php
/* SVN FILE $Id: DateNormalizedToLocalized.php 2 2010-06-14 08:04:19Z SerialGraphics $ */
/**
 * Filter to filter date formats
 *
 * @filesource
 * @copyright		Serial Graphics Copyright 2010
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			May 17, 2010
 * @version			$Revision: 2 $
 * @modifiedby		$LastChangedBy: SerialGraphics $
 * @lastmodified	$Date: 2010-06-14 10:04:19 +0200 (Mon, 14 Jun 2010) $
 */

 
/**
 * Changes a date from normalized format to localized version.
 * 
 */
class SG_Filter_DateNormalizedToLocalized extends Zend_Filter_NormalizedToLocalized
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
    	if(!$_value)
    	{
    		return null;
    	}
    	
    	// check if valid date
    	$validate = new Zend_Validate_Date('yyyy-MM-dd');
    	if(!$validate->isValid($_value))
    	{
    		return $_value;
    	}
    	
        $date = new Zend_Date($_value, 'yyyy-MM-dd');
        return $date->toString('dd/MM/yyyy');
    }
}