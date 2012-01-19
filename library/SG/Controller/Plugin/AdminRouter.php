<?php
/* SVN FILE $Id: AdminRouter.php 28 2010-07-22 09:31:07Z SerialGraphics $ */
/**
 * @filesource
 * @copyright		Serial Graphics Copyright 2010
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			Jul 21, 2010
 */

/**
 * Plugin to change to prefix the controller if it is an admin route
 * 
 */
class SG_Controller_Plugin_AdminRouter extends Zend_Controller_Plugin_Abstract
{
	/**
	 * What modules we don't want to prefix
	 */
	protected $_whiteModules = array('admin');
	
	/**
	 * What controllers we don't want prefix
	 */
	protected $_whiteControllers = array('error');
	
	/**
	 * Pre dispatch  method
	 * 
	 * @see Controller/Plugin/Zend_Controller_Plugin_Abstract#preDispatch($request)
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
    	// check if a admin controller is requested
    	if(!$request->getParam('isAdmin') === true)
    	{
    		return;
    	}
    	
    	// change the controller name
    	$module = $request->getModuleName();
    	$controller = $request->getControllerName();
    	// check whitelist
    	if(
    		in_array($module, $this->_whiteModules) 
    		|| in_array($controller, $this->_whiteControllers)
    	)
    	{
    		return;
    	}
    	
    	
    	$request->setControllerName('admin-' . $controller);

   }
}