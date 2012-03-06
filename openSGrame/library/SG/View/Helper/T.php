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
 * SG_View_Helper_T
 *
 * Translate helper (shorter version of $view->translate)
 *
 * @category SG
 * @package  View
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_View_Helper_T extends Zend_View_Helper_Translate 
{
    /**
     * Translate a message
     * You can give multiple params or an array of params.
     * If you want to output another locale just set it as last single parameter
     * Example 1: translate('%1\$s + %2\$s', $value1, $value2, $locale);
     * Example 2: translate('%1\$s + %2\$s', array($value1, $value2), $locale);
     *
     * @param  string $messageid Id of the message to be translated
     * @return string Translated message
     */
    public function t($messageid = null)
    {
        $args = func_get_args();
        
        //remove first argument ($messageId)
        array_shift($args);

        return $this->translate($messageid, $args);
    }
}