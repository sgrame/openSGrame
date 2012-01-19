<?php
/* SVN FILE $Id: FormCheckbox.php 2 2010-06-14 08:04:19Z SerialGraphics $ */
/**
 * Form checkbox helper
 *
 * @filesource
 * @copyright		Serial Graphics Copyright 2009
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			Sep 8, 2009
 * @version			$Revision: 2 $
 * @modifiedby		$LastChangedBy: SerialGraphics $
 * @lastmodified	$Date: 2010-06-14 10:04:19 +0200 (Mon, 14 Jun 2010) $
 */


/**
 * Helper to generate a "checkbox" element
 *
 * @category   SG
 * @package    SG_View
 * @subpackage Helper
 */
class SG_View_Helper_FormCheckbox extends Zend_View_Helper_FormCheckbox
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
        if(isset($attribs['class']))
        {
            $attribs['class'] .= ' input-checkbox';
        }
        else
        {
            $attribs['class'] = 'input-checkbox';
        }
        
        // remove the helper attribute
        if(isset($attribs['helper']))
        {
            unset($attribs['helper']);
        }
        
        return parent::formCheckbox($name, $value, $attribs, $checkedOptions);
    }

}
