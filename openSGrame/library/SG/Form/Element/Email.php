<?php
/**
 * @category SG
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Form_Element_Email
 *
 * Email form element
 * 
 * This element has EmailAddress filter
 * The validator options are set depending on the environment:
 * => If the environment is development, non existing domain names are allowed.
 *
 * @category SG
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
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
        if(APPLICATION_ENV === 'development') {
            $emailValidatorOptions = array(
                Zend_Validate_Hostname::ALLOW_DNS 
                | Zend_Validate_Hostname::ALLOW_LOCAL
            );
        }
        $this->addValidator('EmailAddress', false, $emailValidatorOptions);
    }
}
