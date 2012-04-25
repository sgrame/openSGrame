<?php
/**
 * @category Default
 * @package  Form_Element
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * Default_Form_Element_RadioGender
 *
 * Gender radio buttons form element
 *
 * @category Default
 * @package  Form_Element
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class Default_Form_Element_RadioGender extends Zend_Form_Element_Radio
{
    /**
     * Element init
     * 
     * @param void
     * 
     * @return void
     */
    public function init()
    {
        $translator = SG_Translator::getInstance();
        $options = array(
            'M' => $translator->t('Male'),
            'F' => $translator->t('Female')
        );
        $this->addMultiOptions($options);
    }
}
