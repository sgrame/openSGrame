<?php
/* SVN FILE $Id: Date.php 2 2010-06-14 08:04:19Z SerialGraphics $ */
/**
 * Date picker form element
 *
 * @filesource
 * @copyright		Serial Graphics Copyright 2010
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			May 15, 2010
 * @version			$Revision: 2 $
 * @modifiedby		$LastChangedBy: SerialGraphics $
 * @lastmodified	$Date: 2010-06-14 10:04:19 +0200 (Mon, 14 Jun 2010) $
 */

/**
 * Form element with an extra class so it is detected as an date field
 * 
 */
class SG_Form_Element_Date extends Zend_Form_Element_Xhtml
{
    /**
     * Default form view helper to use for rendering
     * @var string
     */
    public $helper = 'formDate';
    
    /**
     * Element init
     */
	public function init()
    {
    	$this->setAttrib('maxlength', 10);
    }
}
