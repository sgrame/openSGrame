<?php
/* SVN FILE $Id: Auth.php 2 2010-06-14 08:04:19Z SerialGraphics $ */
/**
 * Authentication helper
 *
 * @filesource
 * @copyright		Serial Graphics Copyright 2009
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			Dec 12, 2009
 * @version			$Revision: 2 $
 * @modifiedby		$LastChangedBy: SerialGraphics $
 * @lastmodified	$Date: 2010-06-14 10:04:19 +0200 (Mon, 14 Jun 2010) $
 */

class SG_Controller_Action_Helper_Auth extends Zend_Controller_Action_Helper_Abstract
{
	/**
	 * Identity
	 * 
	 * @var Zend_Auth
	 */
	protected $_identity;
	
	/**
	 * Init
	 * 
	 * @see Controller/Action/Helper/Zend_Controller_Action_Helper_Abstract#init()
	 */
	public function init()
	{
		
	}
	
	/**
	 * Get identity
	 * 
	 * @param	void
	 * @return	Zend_Auth
	 */
	public function getIdentity()
	{
		// check if identity is already set
		if(isset($_identity))
		{
			return $this->_identity; 
		}
		
		// get the auth
		$auth = Zend_Auth::getInstance();
		
		// check if there is an identity
		if (!$auth->hasIdentity()) 
		{
			return 'Guest';
		}
		
		// store the retrieved identity
		$this->setIdentity($auth->getIdentity());
		
		// return it
		return $this->_identity;
	}
	
	/**
	 * Method to store the identity
	 * 
	 * @param	
	 * @return	self
	 */
	public function setIdentity($_identity)
	{
		$this->_identity = $_identity;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function direct()
	{
		return $this->getIdentity();
	}
}