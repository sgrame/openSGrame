<?php
/* SVN FILE $Id: NameNum.php 2 2010-06-14 08:04:19Z SerialGraphics $ */
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
 * @lastmodified	$Date: 2010-06-14 10:04:19 +0200 (Mon, 14 Jun 2010) $
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