<?php
/* SVN FILE $Id: Layout.php 28 2010-07-22 09:31:07Z SerialGraphics $ */
/**
 * Context plugin
 *
 * @filesource
 * @copyright		Serial Graphics Copyright 2009
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			Dec 8, 2009
 * @version			$Revision: 28 $
 * @modifiedby		$LastChangedBy: SerialGraphics $
 * @lastmodified	$Date: 2010-07-22 11:31:07 +0200 (Thu, 22 Jul 2010) $
 */

class SG_Controller_Plugin_Layout extends Zend_Controller_Plugin_Abstract
{
	/**
	 * Predispatch plugin
	 * Changes the layout if a specific layout is defined for the current route
	 * 
	 * @see Controller/Plugin/Zend_Controller_Plugin_Abstract#preDispatch($request)
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		if($request->getParam('layout')) 
		{
			$layout = Zend_Layout::getMvcInstance();
			$layout->setLayout($request->getParam('layout'));
		}
	}
}