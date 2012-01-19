<?php
/* SVN FILE $Id: AclAdmin.php 2 2010-06-14 08:04:19Z SerialGraphics $ */
/**
 * Admin ACL
 *
 * @filesource
 * @copyright		Serial Graphics Copyright 2009
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			Dec 30, 2009
 * @version			$Revision: 2 $
 * @modifiedby		$LastChangedBy: SerialGraphics $
 * @lastmodified	$Date: 2010-06-14 10:04:19 +0200 (Mon, 14 Jun 2010) $
 */

class SG_Controller_Plugin_AclAdmin extends Zend_Controller_Plugin_Abstract
{
	/**
	 * Module names that this acl should handle
	 */
	private $_modules = array(
		'admin'
	);
	
	/**
	 * Controller that should not be affected by this
	 */
	private $_whitelist = array(
		'login', 
		'logout',
		'no-access',
	);
	
	/**
	 * Pre dispatch  method
	 * 
	 * @see Controller/Plugin/Zend_Controller_Plugin_Abstract#preDispatch($request)
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
    	$module = strtolower($request->getModuleName());
		$controller = strtolower($request->getControllerName());

		// check if we get a request for a module we are watching
		if(!in_array($module, $this->_modules)) {
			return;
		}
		
		// check if we get a request for a controller that is whitelisted
		if(in_array($controller, $this->_whitelist))
		{
			return;
		}
		
		// check authentication
		$auth = Zend_Auth::getInstance();
		
		if (!$auth->hasIdentity()) 
		{
			$this->_response->setRedirect('/admin/login');
			return;
        }
        
        // check acl
        if('admin' != $auth->getIdentity()->role)
        {
        	$this->_response->setRedirect('/admin/no-access');
        	return;
        }
   }
}