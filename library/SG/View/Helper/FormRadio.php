<?php
/* SVN FILE $Id: FormRadio.php 2 2010-06-14 08:04:19Z SerialGraphics $ */
/**
 * Form radio element View helper
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
 * Helper to generate a set of radio button elements
 *
 * @category   SG
 * @package    SG_View
 * @subpackage Helper
 */
class SG_View_Helper_FormRadio extends Zend_View_Helper_FormRadio
{
    /**
     * Generates a set of radio button elements.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are extracted in place of added parameters.
     *
     * @param mixed $value The radio value to mark as 'checked'.
     *
     * @param array $options An array of key-value pairs where the array
     * key is the radio value, and the array value is the radio text.
     *
     * @param array|string $attribs Attributes added to each radio.
     *
     * @return string The radio buttons XHTML.
     */
    public function formRadio($name, $value = null, $attribs = null,
        $options = null, $listsep = "<br />\n")
    {
        // add the input-checkbox class
        if(isset($attribs['class']))
        {
            $attribs['class'] .= ' input-radio';
        }
        else
        {
            $attribs['class'] = 'input-radio';
        }
        
        // remove the helper attribute
        if(isset($attribs['helper']))
        {
            unset($attribs['helper']);
        }
        
        return parent::formRadio($name, $value, $attribs, $options, $listsep);
    }
}
