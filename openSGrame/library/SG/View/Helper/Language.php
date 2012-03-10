<?php
/**
 * @category SG
 * @package  View
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_View_Helper_Language
 *
 * Helper to get the language label by its code key
 *
 * @category SG
 * @package  View
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_View_Helper_Language extends Zend_View_Helper_Abstract 
{
    /**
     * @param  void
     * 
     * @return array of possible languages
     */
    public function language($code)
    {
        return Zend_Locale::getTranslation($code, 'Language');
    }
}