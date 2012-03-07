<?php
/**
 * Form view helper to render an datepicker enabled form field
 *
 * @filesource
 * @copyright		Serial Graphics Copyright 2010
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			May 15, 2010
 * @version			$Revision: 2 $
 * @modifiedby		$LastChangedBy: SerialGraphics $
 * @lastmodified	$Date: 2012-03-06 23:31:33 +0100 (Tue, 06 Mar 2012) $
 */

/**
 * Helper to generate a "date" element
 *
 * @category   SG
 * @package    SG_View
 * @subpackage Helper
 */
class SG_View_Helper_FormDate extends Zend_View_Helper_FormElement
{
    /**
     * Generates a 'date' element.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are used in place of added parameters.
     *
     * @param mixed $value The element value.
     *
     * @param array $attribs Attributes for the element tag.
     *
     * @return string The element XHTML.
     */
    public function formDate($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable

        // build the element
        $disabled = '';
        if ($disable) {
            // disabled
            $disabled = ' disabled="disabled"';
        }
        
        if(!($attribs))
        {
        	$attribs = array();
        }
        
        if(!isset($attribs['class']))
        {
        	$attribs['class'] = 'datepicker';
        }
        else
        {
        	$attribs['class'] = 'datepicker ' . $attribs['class'];
        }
        
        // XHTML or HTML end tag?
        $endTag = ' />';
        if (($this->view instanceof Zend_View_Abstract) && !$this->view->doctype()->isXhtml()) {
            $endTag= '>';
        }

        $xhtml = '<input type="text"'
                . ' name="' . $this->view->escape($name) . '"'
                . ' id="' . $this->view->escape($id) . '"'
                . ' value="' . $this->view->escape($value) . '"'
                . $disabled
                . $this->_htmlAttribs($attribs)
                . $endTag;

        return $xhtml;
    }
}
