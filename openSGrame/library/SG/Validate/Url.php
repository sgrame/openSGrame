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
 * SG_Validate_Url
 *
 * Url validator
 * Checks if an url contains
 *  - protocol
 *  - domain name
 *  - path
 *
 * @category SG
 * @package  Validate
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Validate_Url extends SG_Validate_Abstract 
{
    /**
     * Error messages keys
     * 
     * @var string
     */
    const INVALID_URL    = 'error_not_a_url';
    
    /**
     * Error messages
     * 
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID_URL    => "'%value%' is not a valid URL",
    );
    
    /**
     * Validation patterns
     * 
     * @var string
     */
    const PATTERN = '/^http(s?):\/\/([_a-zA-Z0-9-]+\.)*([_a-zA-Z0-9-])+\.([a-zA-Z]{2,4})(\/~[_a-zA-Z0-9-]+)?(\/[_a-zA-Z0-9-]+)*(\/?)$/';
    
     
    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if it contains a phone number containing only numbers
     * with optional leading +
     *
     * @param  string $value
     * 
     * @return bool
     */
    public function isValid($value)
    {
        $valueString = (string) $value;
        $this->_setValue($valueString);

        //if match is false => string has conflict with given pattern
        if (!preg_match(self::PATTERN, $valueString)) {
            $this->_error(self::INVALID_URL);
            return false;
        }

        return true;
    }
}
