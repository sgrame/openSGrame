<?php

/**
 * View helper to return an array of messages, from the FlashMessenger action
 * helper session variable.
 */
class SG_View_Helper_Messages extends Zend_View_Helper_Abstract 
{
    /**
     * Get the flash messages (if any)
     * 
     * @param  void
     * @return array
     */
    public function messages() 
    {
        $messenger = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'FlashMessenger'
        );
        /* @var $messenger Zend_Controller_Action_Helper_FlashMessenger */
      
        // get the messages
        $messages = $messenger->getMessages();
        $messenger->clearMessages();
        if($messages) {
            return $this->_cleanMessages($messages);
        }
        
        // get the current messages
        $messages = $messenger->getCurrentMessages();
        $messenger->clearCurrentMessages();
                
        return $this->_cleanMessages($messages);
    }
    
    /**
     * Helper to clean up the messages array retrieved from the flashMessenger
     * 
     * @param  array
     * @return array
     */
    protected function _cleanMessages($messages) {
        if(empty($messages)) {
            return array();
        }
        
        return $messages;
    }
}
