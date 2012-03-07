<?php
/**
 * @category SG
 * @package  Validate
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Validate_Phone
 *
 * Phone number validator
 * Checks if a phone number:
 *  - contains numbers only
 *  - may start with + (international)
 *  - the length is between 6 and 20 digits
 *
 * @category SG
 * @package  Validate
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Validate_Phone extends Zend_Validate_StringLength
{
    /**
     * Error messages keys
     * 
     * @var string
     */
    const INVALID_CHARS    = 'phone_error_invalid_chars';
    const INVALID_START    = 'phone_error_invalid_start';
    
    /**
     * Error messages
     * 
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID_CHARS    => "'%value%' contains none numerique values",
        self::INVALID_START    => "'%value%' should start with a leading zero (0)",
    );
    
    /**
     * Validation patterns
     * 
     * @var string
     */
    const PATTERN = '/^[\+]?[0-9]+$/';
    
    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if it contains a phone number containing only numbers
     * with optional leading +
     *
     * @param string $value
     * 
     * @return boolean
     */
    public function isValid($value)
    {
        $valueString = (string) $value;
        $this->_setValue($valueString);

        //if match is false => string has conflict with given pattern
        if (!preg_match(self::PATTERN, $valueString)) {
            $this->_error(self::INVALID_CHARS);
            return false;
        }
        
        // check if the first digit is a 0
        if ('0' != substr((string)$value, 0, 1)) {
            $this->_error(self::INVALID_START);
            return false;
        }

        return true;
    }
}
