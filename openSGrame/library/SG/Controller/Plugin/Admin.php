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
 * SG_Controller_Plugin_AdminRouter
 *
 * Controller plugin to change the controller class for admin controllers
 *
 * @category SG
 * @package  Controller
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Controller_Plugin_Admin extends Zend_Controller_Plugin_Abstract
{
    /**
     * Controllers we dont want to prefix
     * 
     * @var array
     */
    protected $_blacklist = array('error');
  
    /**
     * Pre dispatch  method
     * 
     * @see Controller/Plugin/Zend_Controller_Plugin_Abstract#preDispatch($request)
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        // check if blacklisted
        if(in_array($request->getControllerName(), $this->_blacklist)) {
            return;
        }
      
        // check if a admin controller is requested
        if(true !== (bool)$request->getParam('isAdmin')) {
            return;
        }
        
        // change the controller name
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        
        $request->setControllerName('admin_' . $controller);
   }
}