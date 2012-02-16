<?php
/**
 * @category SG
 * @package  Controller
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Controller_Plugin_Multilanguage
 *
 * Controller plugin to support multilingual websites
 * Based on @link http://www.m4d3l-network.com/developpement/php/zend-framework/add-language-route-to-your-zend-framework-project/
 *
 * @category SG
 * @package  Controller
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */

class SG_Controller_Plugin_Multilanguage extends Zend_Controller_Plugin_Abstract{
    /**
     * Router startup
     * 
     * @param Zend_Controller_Request_Abstract
     */
    public function routeStartup (Zend_Controller_Request_Abstract $request)
    {
        if (substr($request->getRequestUri(), 0, -1) == $request->getBaseUrl()){
            $request->setRequestUri($request->getRequestUri() . "en" . "/");
            $request->setParam("language", "en");
        }
    }
 
    /**
     * Router shutdown
     * 
     * @param Zend_Controller_Request_Abstract
     */
    public function routeShutdown (Zend_Controller_Request_Abstract $request)
    {
        $language  = $request->getParam("language", Zend_Registry::getInstance()->Zend_Locale->getLanguage());
        $locale    = new Zend_Locale($language);
        Zend_Registry::getInstance()->Zend_Locale->setLocale($locale);
        $translate = Zend_Registry::getInstance()->Zend_Translate;
        $translate->getAdapter()->setLocale(Zend_Registry::getInstance()->Zend_Locale);
        Zend_Controller_Router_Route::setDefaultTranslator($translate);
    }
}