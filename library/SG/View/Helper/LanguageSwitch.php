<?php
/**
 * @category SG
 * @package  View
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_View_Helper_LanguageSwitch
 *
 * Helper to get an array of possible languages
 *
 * @category SG
 * @package  View
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_View_Helper_LanguageSwitch extends Zend_View_Helper_Abstract 
{
    /**
     * @param  void
     * 
     * @return array of possible languages
     */
    public function languageSwitch()
    {
        $default   = SG_Variables::getInstance()->get('site_languages_default', 'en');
        $languages = SG_Variables::getInstance()->get('site_languages', array($default));
        $current   = Zend_Controller_Front::getInstance()
                                            ->getRequest()
                                            ->getParam('language');
        
        if(count($languages) === 1) {
            return;
        }
        
        $switch = array(
            'current_key'    => $current, 
            'current_string' => Zend_Locale::getTranslation($current, 'Language', $current),
            'languages'      => array()
        );
        foreach($languages AS $lang) {
            if($lang === $current) {
                continue;
            }
            $switch['languages'][$lang] = Zend_Locale::getTranslation($lang, 'Language', $lang);
        }
        
        return $switch;
    }
}