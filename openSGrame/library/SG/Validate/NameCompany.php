<?php
/**
 * Validate names with optional numbers and & in
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
 * Validates if a string formated as company names. 
 * The extra is that numbers (0-9) and ampersand (&) are allowed.
 *
 */
class SG_Validate_NameCompany extends Zend_Validate_Abstract 
{
	/**
	 * Error messages keys
	 * 
	 * @var 	string
	 */
    const INVALID_CHARS    = 'error_invalid_chars';
    
    /**
     * Error messages
     * 
     * @var		array
     */
    protected $_messageTemplates = array(
        self::INVALID_CHARS    => "'%value%' contains invalid characters",
    );
    
    /**
     * Validation pattern
     * 
     * @var 	string
     */
    protected $_pattern;
    
    /**
     * Constructor
     * 
     */
    public function __construct()
    {
    	$this->_pattern = '/[^\p{L}\p{N}&\s\.\-/u'; 
    }
    
    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if $value contains only alphabetic characters and dash or single quote
     *
     * @param  string $value
     * @return boolean
     */
    public function isValid($value)
    {
        $valueString = (string) $value;
        $this->_setValue($valueString);

        //if match is false => string has conflict with given pattern
        if (!preg_match($this->_pattern, $valueString))
        {
            $this->_error(self::INVALID_CHARS);
            return false;
        }        

        return true;
    }
}