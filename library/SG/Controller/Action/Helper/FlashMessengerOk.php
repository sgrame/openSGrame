<?php
/* SVN FILE $Id: FlashMessengerOk.php 2 2010-06-14 08:04:19Z SerialGraphics $ */
/**
 * Flash messenger OK
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

/**
 * Flash Messenger - implement session-based OK messages
 *
 */
class SG_Controller_Action_Helper_FlashMessengerOk extends Zend_Controller_Action_Helper_FlashMessenger
{
    /**
     * $_namespace - Instance namespace, default is 'messenger-ok'
     *
     * @var string
     */
    protected $_namespace = 'messenger-ok';
    
   /**
     * setNamespace() - change the namespace messages are added to, useful for
     * per action controller messaging between requests
     *
     * @param  string $namespace
     * @return Zend_Controller_Action_Helper_FlashMessenger Provides a fluent interface
     */
    public function setNamespace($namespace = 'messenger-ok')
    {
        $this->_namespace = $namespace;
        return $this;
    }
}