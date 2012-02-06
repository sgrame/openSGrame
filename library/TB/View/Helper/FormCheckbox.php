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
 * TB_View_Helper_FormCheckbox
 *
 * Helper to generate a "checkbox" element
 *
 * @category TB_View
 * @package  Helper
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class TB_View_Helper_FormCheckbox extends Zend_View_Helper_FormCheckbox
{
    /**
     * Generates a 'checkbox' element.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are extracted in place of added parameters.
     * @param mixed $value The element value.
     * @param array $attribs Attributes for the element tag.
     * @return string The element XHTML.
     */
    public function formCheckbox($name, $value = null, $attribs = null, array $checkedOptions = null)
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
        
        return parent::formCheckbox($name, $value, $attribs, $checkedOptions);
    }

}
