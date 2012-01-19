<?php
/* SVN FILE $Id: FormGroupErrors.php 2 2010-06-14 08:04:19Z SerialGraphics $ */
/**
 * helper to get the form error messages grouped 
 *
 * @filesource
 * @copyright		Serial Graphics Copyright 2008
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			September 7, 2009
 * @version			$Revision: 2 $
 * @modifiedby		$LastChangedBy: SerialGraphics $
 * @lastmodified	$Date: 2010-06-14 10:04:19 +0200 (Mon, 14 Jun 2010) $
 */

/**
 * View helper to get form errors grouped
 *
 */
class SG_View_Helper_FormGroupErrors extends Zend_View_Helper_Abstract
{
	/**
	 * Class name
	 * 
	 * @var string
	 */
    protected $_className = 'SG_form_errors';
    
    /**
     * Error title
     * 
     * @var string
     */
    protected $_title = 'core.form.msg.errorGroup';
    
	   
    /**
     * Get a part of the config
     * 
     * @param 	Zend_Form
     * @param	string	optional title
     * @return 	string
     */
    public function formGroupErrors(Zend_Form $_form, $_title = null)
    {	
    	if(!$_form->isErrors())
    	{
    		return;
    	}
    	
    	if(!is_null($_title))
    	{
    	    $this->_title = $_title;
    	}
    	
    	// start the string array
    	$string = array();
    	$string[] = '<div class="flash-messenger flash-messenger-error">';
    	$string[] = '<div class="flash-messenger-inner">';
    	// close button
		$string[] = '<div class="flash-messenger-close"><a><span>' 
			. $this->view->translate('label.flashMessenger.close')
			. '</span></a></div>';
    	
    	// add the title
    	$string[] = '<p>' . $this->view->translate($this->_title) . '</p>';
    	
    	// add the messages
    	$string[] = '<ul class="' . $this->_className . '">';
    	
    	// loop through the elements
    	$elements = $_form->getElements();
    	foreach($elements AS $element)
    	{
    	    /* @var $element Zend_Form_Element */
    		if($element->hasErrors() && $element->getId() !== 'csrf')
    		{
    		    // remove the individual error decorator
    			$element->removeDecorator('Errors');
    			
    			// add the label to the errors
    			$string[] = '<li><span class="element">' 
    						. $this->view->translate(
    							$this->_getElementLabel($element)
    						) 
    						. ' :</span> ';
    			
    			// add the error messages to the output
    			$messages = array();
    			foreach($element->getMessages() AS $message)
    			{
    				$messages[] = $this->view->translate($message); 
    			}
    			$string[] = '<span class="messages">' . implode(', ', $messages) . '</span>';
    			$string[] = '</li>';
    		}
    	}
    	
    	// finish the string
    	$string[] = '</ul>';
    	$string[] = '</div></div>';
    	
    	// return the result
    	return implode("\n", $string);
    }
    
    /**
     * Helper method to get the label sanitized from the element
     * 
     * @param	Zend_Form_Element
     * @return	string
     */
    protected function _getElementLabel(Zend_Form_Element $_element)
    {
    	return trim($_element->getLabel($_element));
    }
}