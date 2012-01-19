<?php
/* SVN FILE $Id: CompanyNumber.php 2 2010-06-14 08:04:19Z SerialGraphics $ */
/**
 * Company number input filter
 *
 * @filesource
 * @copyright		Serial Graphics Copyright 2009
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			Oct 29, 2009
 * @version			$Revision: 2 $
 * @modifiedby		$LastChangedBy: SerialGraphics $
 * @lastmodified	$Date: 2010-06-14 10:04:19 +0200 (Mon, 14 Jun 2010) $
 */
 
/**
 * Removes all non numerique values from a Company Number string.
 * It gleus the parts by the given glue sting (default ".").
 * 
 * Options:
 *   - allowWhiteSpace: allow whitespaces in the number
 * 
 * @category   Zend
 * @package    Zend_Filter
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class SG_Filter_CompanyNumber extends Zend_Filter_Alnum
{
	/**
	 * Glue between number groups
	 * 
	 * @var 	string
	 */
	protected $_glue = '.';
	
	/**
	 * Constructor
	 * 
	 * @param 	string	parts glue
	 */
	public function __construct($_glue = '.')
	{
		$this->_glue = $_glue;
	}
	
    /**
     * Defined by Zend_Filter_Interface
     *
     * @param  string $value
     * @return string
     */
    public function filter($_value)
    {        
        //strip all non-digit chars
        $value = preg_replace("/[^([:digit:])]/", '', $_value);

        //string should now have 10 chars to be valid
        if (strlen($value) == 10)
        {
            //set dashes dashes
            $split = array();
    		$split[] = substr($value, 0, 4);
    		$split[] = substr($value, 4, 3);
    		$split[] = substr($value, 7, 3);
    		$value = implode($this->_glue, $split);
        }

        return $value;
    }
}