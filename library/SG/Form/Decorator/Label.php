<?php
/* SVN FILE $Id: Label.php 2 2010-06-14 08:04:19Z SerialGraphics $ */
/**
 * Form decorator
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
 * SG_Form_Decorator_Label
 *
 * Overrides the default class rendering: 
 * adds an error class (css) when the element isn't valid.
 *
 * @category   SG
 * @package    SG_Form
 * @subpackage Decorator
 */
class SG_Form_Decorator_Label extends Zend_Form_Decorator_Label
{
	/**
	 * Error class name
	 * @var	string
	 */
	protected $errorClassName = 'error';
	
	/**
	 * Required extra string
	 * @var	string
	 */
	protected $requiredString = ' *';
	
	/**
     * Set current form element
     * 
     * @param  Zend_Form_Element|Zend_Form $element 
     * @return Zend_Form_Decorator_Abstract
     * @throws Zend_Form_Decorator_Exception on invalid element type
     */
    public function setElement($element)
    {
        parent::setElement($element);
        
        if(!$this->getOption('requiredSuffix') && $element->isRequired())
        {
            $this->setOption('requiredSuffix', $this->requiredString);
        }
        return $this;
    }
	
    /**
     * Get class with which to define label
     *
     * Appends 'error' to class, depending on whether
     * or not the element is valid.
     *
     * @return string
     */
    public function getClass()
    {
    	// get the parent class
    	$class = array(parent::getClass());
    	
    	// check if the element is valid
        $element = $this->getElement();
        /* @var $element Zend_Form_Element */
        
        if($element->hasErrors())
        {
        	$class[] = $this->errorClassName;
        }

        return implode(' ', $class);
    }
}
