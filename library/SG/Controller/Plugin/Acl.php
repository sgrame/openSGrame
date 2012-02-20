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
 * SG_Controller_Plugin_Acl
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

class SG_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract{
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
        // initiate the ACL
        if(!Zend_Registry::isRegistered('acl')) {
            $acl = new SG_Acl();
            Zend_Registry::set('acl', $acl);
        }
        else {
            $acl = Zend_Registry::get('acl');
        }
        
        // set the acl to the navigation
        $view = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('view');
        $view->navigation()->setAcl(Zend_Registry::get('acl'))->setRole(
            $acl->getCurrentUserRole()
        );
    }
}