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
 * SG_Rule_Parser_Comparison_Equal
 *
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Rule_Parser_Comparison_Equal extends SG_Rule_Parser_Comparison_Abstract
{
    /**
     * Expected pattern
     * 
     * @var string 
     */
    protected $_pattern = SG_Rule_Parser_Patterns::COMPARISON_EQUAL;
    
    /**
     * Split value
     * 
     * @var string 
     */
    protected $_split = '=';
    
    /**
     * Split exclude
     * 
     * @var array 
     */
    protected $_exclude = array('!=');
}