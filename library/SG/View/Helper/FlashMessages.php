<?php
/* SVN FILE $Id: FlashMessages.php 2 2010-06-14 08:04:19Z SerialGraphics $ */
/**
 * Helper to output the FlashMessenger messages (if any)
 *
 * @filesource
 * @copyright		Serial Graphics Copyright 2009
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			Aug 17, 2009
 */

 
/**
 * Helper to get the FlashMessenger messages (if any)
 */
class SG_View_Helper_FlashMessages extends Zend_View_Helper_Abstract
{
	/**
     * Options
     */
    protected $_options = array(
                    'class'	=> null,
                );
                
    /**
     * Possible messenger boxes
     */
	protected $_types = array(
		'default' 	=> 'FlashMessenger', 
		'ok'		=> 'FlashMessengerOk', 
		'warning'	=> 'FlashMessengerWarning', 
		'error'		=> 'FlashMessengerError',
	);
	
	/**
	 * Output the flash messages
	 * 
	 * @param	array	options
	 * @return 	string
	 */
	public function flashMessages($_options = array())
	{	
	    // check for options
	    if(is_array($_options) && 0 < count($_options))
	    {
	        $this->_options = $_options;
	    }
	    
	    $output = array();
	    
	    if(isset($this->_options['type']))
	    {
	    	// check if the type exists
	    	if(isset($this->_types[$this->_options['type']]))
	    	{
	    		$output[] = $this->_renderBox(
	    			$this->_types[$this->_options['type']]
	    		);
	    	}
	    }
	    else
	    {	
	    	foreach($this->_types AS $type => $helperName)
	    	{
	    		$output[] = $this->_renderBox($helperName);
	    	}
	    }
	    
	    return implode("\n", $output);
	}
	
	/**
	 * Render a messenger box
	 * 
	 * @param	string	helper name
	 * @return	string
	 */
	protected function _renderBox($_name)
	{
		$output = array();
		
		$messenger = Zend_Controller_Action_HelperBroker::getStaticHelper($_name);
		/* @var $messenger Zend_Controller_Action_Helper_FlashMessenger */
		
		if(!$messenger->hasCurrentMessages() && !$messenger->hasMessages())
		{
			return null;
		}
		
		// get the messages
		$messages = $messenger->getMessages();
		// if didn't find messages we need to get the current messages
		if(!$messages)
		{
			$messages = $messenger->getCurrentMessages();
		}
		
		// pre build
		$filter = new Zend_Filter_Word_CamelCaseToDash();
		$elementId = strtolower($filter->filter($_name));
		
		// Build the output
		$output = array();
		
		$start = '<div id="' . $elementId . '"';
		$start .= 'class="flash-messenger';
		
		if(isset($this->_options['class']) && !is_null($this->_options['class']))
		{
			$start .= ' ' . $this->_options['class'];
		}
		$start .= '"><div class="flash-messenger-inner">';
		
		$output[] = $start;
		
		// close button
		$output[] = '<div class="flash-messenger-close"><a><span>' 
			. $this->view->translate('label.flashMessenger.close')
			. '</span></a></div>';

		foreach($messages AS $message)
		{
			$output[] = '<p>' . $this->view->translate($message) . '</p>';
		}
		
		$output[] = '</div></div><!-- // ' . $elementId . ' -->';
		
		return implode("\n", $output);
	}
}