<?php
/* SVN FILE $Id: Url.php 2 2010-06-14 08:04:19Z SerialGraphics $ */
/**
 * Url validator
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
 * Checks if a url contains
 *  - protocol
 *  - domainname
 *  - path
 */
class SG_Validate_Url extends SG_Validate_Abstract 
{
	/**
	 * Error messages keys
	 * 
	 * @var 	string
	 */
    const INVALID_URL    = 'error_not_a_url';
    
    /**
     * Error messages
     * 
     * @var		array
     */
    protected $_messageTemplates = array(
        self::INVALID_URL    => "'%value%' is not a valid URL",
    );
    
    /**
     * Validation patterns
     * 
     * @var 	string
     */
    const PATTERN = '/^http(s?):\/\/([_a-zA-Z0-9-]+\.)*([_a-zA-Z0-9-])+\.([a-zA-Z]{2,4})(\/~[_a-zA-Z0-9-]+)?(\/[_a-zA-Z0-9-]+)*(\/?)$/';
    
     
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
            $this->_error(self::INVALID_URL);
            return false;
        }

        return true;
    }
}