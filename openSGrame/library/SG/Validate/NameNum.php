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
 * SG_Validate_NameNum
 *
 * Validates a string to be a name.
 * Extends the Zend_Validate_AlphaNum with support for ., - and & 
 * and set the allow whitespace to true
 *
 * @category SG
 * @package  Validate
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
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