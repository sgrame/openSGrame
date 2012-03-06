<?php
/**
 * @category TB_View
 * @package  Helper
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * TB_View_Helper_FormMultiCheckbox
 *
 * Helper to generate a set of checkbox button elements
 *
 * @category TB_View
 * @package  Helper
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class TB_View_Helper_FormMultiCheckbox extends Zend_View_Helper_FormMultiCheckbox
{
    /**
     * Generates a set of checkbox button elements.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are extracted in place of added parameters.
     *
     * @param mixed $value The checkbox value to mark as 'checked'.
     *
     * @param array $options An array of key-value pairs where the array
     * key is the checkbox value, and the array value is the radio text.
     *
     * @param array|string $attribs Attributes added to each radio.
     *
     * @return string The radio buttons XHTML.
     */
    public function formMultiCheckbox(
        $name, 
        $value = null, 
        $attribs = null, 
        $options = null, 
        $listsep = "<br />\n"
    ) 
    {
        // add the input-checkbox class
        if(isset($attribs['class'])) {
            $attribs['class'] .= ' input-checkbox';
        }
        else {
            $attribs['class'] = 'input-checkbox';
        }
        
        // remove the helper attribute
        if(isset($attribs['helper'])) {
            unset($attribs['helper']);
        }
        
        return parent::formMultiCheckbox(
            $name, $value, $attribs, $options, $listsep
        );
    }
}
