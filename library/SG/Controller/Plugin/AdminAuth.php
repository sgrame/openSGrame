<?php
/* SVN FILE $Id: AdminAuth.php 64 2011-07-26 21:23:17Z SerialGraphics $ */
/**
 * Auth admin plugin
 *
 * @filesource
 * @copyright		Serial Graphics Copyright 2009
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			Dec 8, 2009
 * @version			$Revision: 64 $
 * @modifiedby		$LastChangedBy: SerialGraphics $
 * @lastmodified	$Date: 2011-07-26 23:23:17 +0200 (Tue, 26 Jul 2011) $
 */

class SG_Controller_Plugin_AdminAuth extends Zend_Controller_Plugin_Abstract
{
	/**
	 * Pre dispatch  method
	 * 
	 * @see Controller/Plugin/Zend_Controller_Plugin_Abstract#preDispatch($request)
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
    	$isAdmin = (bool)$request->getParam('isAdmin');

		// check if we get a request for a module we are watching
		if(!$isAdmin) {
			return;
		}
		
		// check authentication
		$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity() && (int)$auth->getIdentity()->role_id === 1) 
		{
			return;
    }
 
        // no authentication, redirect to login
        $this->_response->setRedirect('/login'); 
   }
}