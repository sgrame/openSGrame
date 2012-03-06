<?php
/* SVN FILE $Id: Email.php 32 2010-07-25 14:32:17Z SerialGraphics $ */
/**
 * @filesource
 * @copyright        Serial Graphics Copyright 2010
 * @author            Serial Graphics <info@serial-graphics.be>
 * @link            http://www.serial-graphics.be
 * @since            Jul 24, 2010
 */

/**
 * Email form element
 * 
 * This element has EmailAddress filter
 * The validator options are set depending on the environment:
 * => If the environment is development, non existing domain names are allowed.
 */
class SG_Form_Element_Email extends Zend_Form_Element_Text
{
    /**
     * Element init
     */
    public function init()
    {
        // add default filter
        $this->addFilter('StringTrim');
        
        // add email validator depending on environment
        $emailValidatorOptions = array();
        if(APPLICATION_ENV === 'development')
        {
            $emailValidatorOptions = array(
                Zend_Validate_Hostname::ALLOW_DNS 
                | Zend_Validate_Hostname::ALLOW_LOCAL
            );
        }
        $this->addValidator('EmailAddress', false, $emailValidatorOptions);
    }
}
