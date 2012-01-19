<?php
/* SVN FILE $Id: FormMultiCheckbox.php 2 2010-06-14 08:04:19Z SerialGraphics $ */
/**
 * Checkbox helper
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
 * Helper to generate a set of checkbox button elements
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class SG_View_Helper_FormMultiCheckbox extends Zend_View_Helper_FormMultiCheckbox
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
    public function formMultiCheckbox($name, $value = null, $attribs = null,
        $options = null, $listsep = "<br />\n")
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
        
        return parent::formMultiCheckbox(
            $name, $value, $attribs, $options, $listsep
        );
    }
}
