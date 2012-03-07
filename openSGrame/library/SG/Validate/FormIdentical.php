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
 * SG_Validate_FormIdentical
 *
 * Compare form values
 * Validates if a given value is identical compared to an other form value key
 *
 * @category SG
 * @package  Validate
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Validate_FormIdentical extends Zend_Validate_Abstract
{
    /**
     * Error codes
     * 
     * @var string
     */
    const NOT_SAME_VALUE      = 'FormIdentical_notSameValues';

    /**
     * Error messages
     * 
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_SAME_VALUE             => 'Values do not match',
    );
    
    /**
     * name of the form field against who to validate
     * 
     * @var string
     */
    protected $_contextKey;
    
    /**
     * Constructor
     * 
     * @param string 
     *    context key against to check
     * 
     * @return void
     */
    public function __construct($_contextKey = null)
    {
        $this->_contextKey = $_contextKey;
    }
    
    /**
     * Validator
     * 
     * @param string    
     *     value to validate
     * @param array
     *     context
     * 
     * @return bool
     *     valid
     */
    public function isValid($_value, $_context = array())
    {
        if(!is_array($_context) 
            || !array_key_exists($this->_contextKey, $_context)
        ) {
            $this->_error(self::NOT_SAME_VALUE);
            return false;
        }
        
        if ($_value !== $_context[$this->_contextKey]) {
            $this->_error(self::NOT_SAME_VALUE);
            return false;
        }
        
        return true;
        
    }
}