<?php
/* SVN FILE $Id: Auth.php 2 2010-06-14 08:04:19Z SerialGraphics $ */
/**
 * Default SG Controller extention
 *
 * @filesource
 * @copyright		Serial Graphics Copyright 2008
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			Jun 19, 2009
 * @package			SG
 * @subpackage		Form
 * @version			$Revision: 2 $
 * @modifiedby		$LastChangedBy: SerialGraphics $
 * @lastmodified	$Date: 2010-06-14 10:04:19 +0200 (Mon, 14 Jun 2010) $
 */
 
/**
 * base controller that default checks if the user is logged in
 * 
 */
class SG_Controller_Auth extends Zend_Controller_Action 
{
	/**
	 * Default redirect settings
	 * 
	 * @var 	array
	 */
	protected $_notAuthRedirect = array(
		'module'		=> 'admin',
		'controller'	=> 'login',
		'action'		=> 'index'
	);
	
	/**
	 * Default init
	 */
	public function init()
	{
		// check access
		$auth = Zend_Auth::getInstance();
		if(!$auth->hasIdentity())
		{
			$this->_helper->redirector(
				$this->_notAuthRedirect['action'],
				$this->_notAuthRedirect['controller'],
				$this->_notAuthRedirect['module']
			);
		}
		
		// add the user to the view
		$this->view->user = $auth->getIdentity();
	}
}