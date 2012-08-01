<?php
/**
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Rule_Parser_Function_Min
 *
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Rule_Parser_Function_Min extends SG_Rule_Parser_Function_Abstract
{
    /**
     * Expected pattern
     * 
     * @var string 
     */
    protected $_pattern = SG_Rule_Parser_Patterns::FUNCTION_MIN;
    
    /**
     * Function prefix
     * 
     * @var array 
     */
    protected $_prefix = SG_Rule_Function_Min::FUNCTION_NAME;
}