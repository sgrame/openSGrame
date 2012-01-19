<?php
/* SVN FILE $Id: Page.php 2 2010-06-14 08:04:19Z SerialGraphics $ */
/**
 * View helper to create the url to a page
 *
 * @filesource
 * @copyright		Serial Graphics Copyright 2008
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			Jun 19, 2009
 * @version			$Revision: 2 $
 * @modifiedby		$LastChangedBy: SerialGraphics $
 * @lastmodified	$Date: 2010-06-14 10:04:19 +0200 (Mon, 14 Jun 2010) $
 */
 
/**
 * Create an URL to a certain page
 *
 */ 
class SG_View_Helper_Page extends Zend_View_Helper_Abstract
{
    /**
     * Method to create a full path to a page controller
     * 
     * @param 	string	page name
     * @return 	string	url
     */
	public function page($_pageName)
	{
		$url = APPLICATION_URL . '/' . $_pageName;
		
		return $url;
	}
}