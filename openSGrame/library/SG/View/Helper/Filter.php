<?php
/**
 * @category SG
 * @package  View_Helper
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_View_Helper_Filter
 *
 * Helper Filter for users input
 *   - strips tags
 *   - replaces newlines with <br />
 *
 * @category SG
 * @package  View_Helper
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_View_Helper_Filter extends Zend_View_Helper_Abstract
{
    /**
     * Filters and purifies user inpt
     *
     * @param    string
     * @param    array    options
     * @return     string     The filtered string
     */
    public function filter($_string, $_options = array())
    {
        // filter tags
        $filter = new Zend_Filter_StripTags();
        $_string = $filter->filter($_string);
        
        // filter newlines
        $_string = str_replace(PHP_EOL, '<br />', $_string);
        
        return $_string;
    }

}
