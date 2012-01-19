<?php
/* SVN FILE $Id: Phone.php 21 2010-07-01 22:34:24Z SerialGraphics $ */
/**
 * Phone number validator
 *
 * @filesource
 * @copyright		Serial Graphics Copyright 2008
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			Jun 19, 2009
 * @package			SG
 * @subpackage		Validate
 * @version			$Revision: 21 $
 * @modifiedby		$LastChangedBy: SerialGraphics $
 * @lastmodified	$Date: 2010-07-02 00:34:24 +0200 (Fri, 02 Jul 2010) $
 */

/**
 * Checks if a phone number:
 *  - contains numbers only
 *  - may start with + (international)
 *  - the length is between 6 and 20 digits
 */
class SG_Validate_Phone extends Zend_Validate_StringLength
{
	/**
	 * Error messages keys
	 * 
	 * @var 	string
	 */
    const INVALID_CHARS    = 'phone_error_invalid_chars';
    const INVALID_START    = 'phone_error_invalid_start';
    
    /**
     * Error messages
     * 
     * @var		array
     */
    protected $_messageTemplates = array(
        self::INVALID_CHARS    => "'%value%' contains none numerique values",
        self::INVALID_START    => "'%value%' should start with a leading zero (0)",
    );
    
    /**
     * Validation patterns
     * 
     * @var 	string
     */
    const PATTERN = '/^[\+]?[0-9]+$/';
    
    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if it contains a phone number containing only numbers
     * with optional leading +
     *
     * @param  string $value
     * @return boolean
     */
    public function isValid($value)
    {
        $valueString = (string) $value;
        $this->_setValue($valueString);

        //if match is false => string has conflict with given pattern
        if (!preg_match(self::PATTERN, $valueString))
        {
            $this->_error(self::INVALID_CHARS);
            return false;
        }
        
        // check if the first digit is a 0
        if ('0' != substr((string)$value, 0, 1))
        {
        	$this->_error(self::INVALID_START);
        	return false;
        }

        return true;
    }
}