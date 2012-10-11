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
 * SG_Validate_Name
 *
 * Validates a string to be a name
 * Extends the Zend_Validate_Alpha and set the allow whitespace to true
 *
 * @category SG
 * @package  Validate
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Validate_Name extends Zend_Validate_Alpha
{
    /**
     * Class constructor
     */
     public function __construct($allowWhiteSpace = true)
    {
        parent::__construct($allowWhiteSpace);
        self::$_filter = new SG_Filter_Name();
    }
}