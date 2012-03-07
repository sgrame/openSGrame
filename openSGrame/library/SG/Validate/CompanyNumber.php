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
 * SG_Validate_CompanyNumber
 *
 * Validator for Company Numbers
 * Checks if a company number has the right syntax and checksum
 *
 * @category SG
 * @package  Validate
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Validate_CompanyNumber extends Zend_Validate_Abstract 
{
    /**
     * Error codes
     * 
     * @var string
     */
    const INVALID = 'companyNumberInvalid';
    
    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID => "'%value%' is not a valid company number",
    );

    /**
     * The validation pattern
     * 
     * @var string
     */
    const PATTERN = '/^[01][0-9]{3}[\.][0-9]{3}[\.][0-9]{3}$/';
    
    
    /**
     * Check if the given value is valid
     * 
     * @param  string $_value
     *
     * @return bool
     */
    public function isValid ($_value)
    {   
        $this->_setValue($_value);
         
        //check if given number matches the pattern for a companyNumber
        if (!preg_match(self::PATTERN, $_value)) {
            $this->_error(self::INVALID);
            return false;
        }
        
        //check if the number matches to mod97 check
        $number = preg_replace("/[^([:digit:])]/", '', $_value);
        $checknr = substr($number,0,8);
        $controle = substr($number,8,2);
        $mod97 = 97-fmod($checknr,97);
        if ($mod97 == 0) {
            $mod97 = 97;
        }
        
        if ($controle != $mod97) {
            $this->_error(self::INVALID);
            return false;
        }
        
        return true;
    }
    
}