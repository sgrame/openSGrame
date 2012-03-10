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
 * SG_Form_Element_SelectLanguage
 *
 * Form element to select a language
 * This will show by default the system languages 
 * (as defined within the variables table)
 *
 * @category SG
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Form_Element_SelectLanguage extends Zend_Form_Element_Select 
{ 
    /**
     * Init the element option
     */
    public function init()
    {
        $translate = Zend_Registry::get('Zend_Translate');
        $languages = SG_Variables::getInstance()->get(
            'site_languages', array()
        );
        $options = array();
        
        if(count($languages) > 1) {
            $options[null] = $translate->translate('-- Choose --');
        }
        
        foreach($languages AS $language) {
            $options[$language] = Zend_Locale::getTranslation($language, 'Language');
        }
        
        $this->setMultiOptions($options);
    }
}