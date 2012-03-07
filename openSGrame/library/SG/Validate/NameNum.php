<?php
/**
 * Validates a string to be a name
 *
 * @filesource
 * @copyright		Serial Graphics Copyright 2008
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			Jun 19, 2009
 * @package			SG
 * @subpackage		Validate
 * @version			$Revision: 2 $
 * @modifiedby		$LastChangedBy: SerialGraphics $
 * @lastmodified	$Date: 2012-03-06 23:31:33 +0100 (Tue, 06 Mar 2012) $
 */

/**
 * Extends the Zend_Validate_AlphaNum with support for ., - and & and set the allow whitespace to true
 *
 */
class SG_Validate_NameNum extends Zend_Validate_Alnum
{
	/**
	 * Class constructor
	 * 
	 */
 	public function __construct($allowWhiteSpace = true)
    {
    	parent::__construct($allowWhiteSpace);
        self::$_filter = new SG_Filter_NameNum();
    }
}