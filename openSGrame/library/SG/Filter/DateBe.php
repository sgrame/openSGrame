<?php
/* SVN FILE $Id: DateBe.php 2 2010-06-14 08:04:19Z SerialGraphics $ */
/**
 * Date be filter
 *
 * @filesource
 * @copyright		Serial Graphics Copyright 2008
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			Jun 19, 2009
 * @package			SG
 * @subpackage		Filter
 * @version			$Revision: 2 $
 * @modifiedby		$LastChangedBy: SerialGraphics $
 * @lastmodified	$Date: 2010-06-14 10:04:19 +0200 (Mon, 14 Jun 2010) $
 */

/**
 * Formats a date as DD/MM/YYYY
 * 
 */
class SG_Filter_DateBe extends Zend_Filter_Alnum
{
	/**
	 * Pattern for the date split
	 * 
	 * @var 	string
	 */
	protected $_patternSplit = "/[,:_\.\/-\s]+/";
	
	/**
	 * Glue between date parts
	 * 
	 * @var 	string
	 */
	protected $_glue = '/';
	
	/**
	 * Constructor
	 * 
	 * @param 	string	parts glue
	 */
	public function __construct($_glue = '/')
	{
		$this->_glue = $_glue;
	}
	
    /**
     * Defined by Zend_Filter_Interface
     *
     * Returns the string $value, removing all but alphabetic and digit characters
     *
     * @param  string $value
     * @return string
     */
    public function filter($_value)
    {
        $dateArray = preg_split($this->_patternSplit, $_value);
        if(3 !== count($dateArray))
        {
        	return $_value;
        }
        
        list($day, $month, $year) = $dateArray;
        
		if ((int)$day < 10)
		{
			$day = "0".(int)$day;
		}
		if ((int)$month < 10)
		{
			$month = "0".(int)$month;
		}
			
		return implode($this->_glue, array($day, $month, (int)$year));
    }
}
