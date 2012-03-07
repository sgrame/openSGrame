<?php
/**
 * URL input filter
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
 * @lastmodified	$Date: 2012-03-06 23:31:33 +0100 (Tue, 06 Mar 2012) $
 */

/**
 * Adds missing http:// to an URL
 * 
 * @category   Zend
 * @package    Zend_Filter
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class SG_Filter_Url extends Zend_Filter_Alnum
{
	/**
	 * Pattern to check if the http:// is in front of the string
	 */
	const PATTERN = '/^http(s?):\/\//';
	
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
    	$url = (string)$_value;
    	
    	if(isset($url{0}))
    	{
	    	if(!preg_match(self::PATTERN, $url))
	    	{
	    		$url = 'http://' . $url;
	    	}
    	}
    	
        return $url;
    }
}
