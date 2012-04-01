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
 * SG_Controller_Plugin_Destination
 *
 * Controller plugin to register an optional destination GET param from the 
 * request url.
 * The destination is stored in the SG_DESTINATION session namespace. 
 *
 * @category SG
 * @package  Controller
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Controller_Plugin_Destination extends Zend_Controller_Plugin_Abstract{
    /**
     * Router startup
     * 
     * @param Zend_Controller_Request_Abstract
     */
    public function routeStartup (Zend_Controller_Request_Abstract $request)
    {
        $destination = $request->getParam('destination');
        if(!empty($destination)) {
            $ns = new Zend_Session_Namespace('SG_DESTINATION');
            $ns->destination = $destination;
            $ns->setExpirationHops(5);
        }
    }
}