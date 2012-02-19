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
 * SG_Controller_Plugin_Language
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

class SG_Controller_Plugin_Language extends Zend_Controller_Plugin_Abstract{
    /**
     * Router startup
     * 
     * @TODO: fix platform so that no language prefix is needed if only one 
     *        language is in use
     * 
     * @param Zend_Controller_Request_Abstract
     */
    public function routeStartup (Zend_Controller_Request_Abstract $request)
    {
        // get the language configuration
        $vars              = SG_Variables::getInstance();
        $languages_default = $vars->get('site_languages_default', 'en');
        $languages         = $vars->get(
            'site_languages', array($languages_default)
        );
        
        // redirect root path to language prefixed version
        if (substr($request->getRequestUri(), 0, -1) == $request->getBaseUrl()) {
            $this->_response->setRedirect(
                $request->getRequestUri() . $languages_default . '/'
            );
            return;
        }
        
        // redirect direct paths to language prefixed version
        if(!preg_match('#^/[a-z]{2}$#', $request->getRequestUri())
            && !preg_match('#^/[a-z]{2}/#', $request->getRequestUri())
        ) {
            $this->_response->setRedirect(
                '/' . $languages_default . $request->getRequestUri()
            );
            return;
        }
        
        // check if the language is supported
        $lang = substr($request->getRequestUri(), 1, 2);
        if(!in_array($lang, $languages)) {
            throw new SG_Controller_Action_NotFound_Exception(
                'Language "' . $lang . '"not supported'
            );
        }
    }
 
    /**
     * Router shutdown
     * 
     * @param Zend_Controller_Request_Abstract
     */
    public function routeShutdown (Zend_Controller_Request_Abstract $request)
    {
        $language  = $request->getParam(
            'language', 
            Zend_Registry::getInstance()->Zend_Locale->getLanguage()
        );
        $locale    = new Zend_Locale($language);
        Zend_Registry::getInstance()->Zend_Locale->setLocale($locale);
        $translate = Zend_Registry::getInstance()->Zend_Translate;
        $translate->getAdapter()->setLocale(Zend_Registry::getInstance()->Zend_Locale);
        Zend_Controller_Router_Route::setDefaultTranslator($translate);
        
        $view = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('view');
    }
}