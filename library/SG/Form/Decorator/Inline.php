<?php
/* SVN FILE $Id: Inline.php 2 2010-06-14 08:04:19Z SerialGraphics $ */
/**
 * Decorator to render elements inline
 *
 * @filesource
 * @copyright		Serial Graphics Copyright 2008
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			Jun 19, 2009
 * @package			SG
 * @subpackage		Decorator
 * @version			$Revision: 2 $
 * @modifiedby		$LastChangedBy: SerialGraphics $
 * @lastmodified	$Date: 2010-06-14 10:04:19 +0200 (Mon, 14 Jun 2010) $
 */

/**
 * Decorator to render elements inline
 * 
 * Can be usefull to render a checkbox with the label after it
 *
 */
class SG_Form_Decorator_Inline extends Zend_Form_Decorator_Abstract
{
	/**
	 * Build the label
	 */
    public function buildLabel()
    {
        $element = $this->getElement();
        $label = $element->getLabel();
        $translator = $element->getTranslator();
        
        if ($translator) {
            $label = $translator->translate($label);
        }
        if ($element->isRequired()) {
            $label .= '*';
        }
        return $element->getView()
                       ->formLabel($element->getName(), $label);
    }

    /**
     * Build the input
     */
    public function buildInput()
    {
        $element = $this->getElement();
        
        $helper  = $element->helper;
        
        return $element->getView()->$helper(
            $element->getName(),
            $element->getValue(),
            $element->getAttribs(),
            $element->options
        );
    }

    /**
     * Build the errors
     */
    public function buildErrors()
    {
        $element  = $this->getElement();
        $messages = $element->getMessages();
        if (empty($messages)) {
            return '';
        }
        return $element->getView()->formErrors($messages);
    }

    /**
     * Build the description
     */
    public function buildDescription()
    {
        $element = $this->getElement();
        $desc    = $element->getDescription();
        if (empty($desc)) {
            return '';
        }
        return '<p class="description">' . $desc . '</p>';
    }

    /**
     * Render the content
     */
    public function render($content)
    {
        $element = $this->getElement();
        if (!$element instanceof Zend_Form_Element) {
            return $content;
        }
        if (null === $element->getView()) {
            return $content;
        }

        $separator = $this->getSeparator();
        $placement = $this->getPlacement();
        $label     = $this->buildLabel();
        $input     = $this->buildInput();
        $errors    = $this->buildErrors();
        $desc      = $this->buildDescription();

        $output = 
        		'<dt id="' . $element->getName() . '-label"></dt>'
        		. "\n"
        		. '<dd id="' . $element->getName() . '-element">'
        		. $input
        		. $label
                . $errors
                . $desc
                . '</dd>';

        switch ($placement) {
            case (self::PREPEND):
                return $output . $separator . $content;
            case (self::APPEND):
            default:
                return $content . $separator . $output;
        }
    }
}