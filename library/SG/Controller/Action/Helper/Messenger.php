<?php
/**
 * Proxy to add a messages to the flashMessenger.
 */

/**
 * Proxy around the Zend_Controller_Action_Helper_FlashMessenger
 * 
 * The proxy implements 4 types of messages:
 * - addInfo()    : Add a message of the type "info"
 * - addSuccess() : Add a message of the type "success"
 * - addWarning() : Add a message of the type "warning"
 * - addError()   : Add a message of the type "error"
 * 
 * It is possible to add custom message types with the addMessage() method.
 */
class SG_Controller_Action_Helper_Messenger
    extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * @var Zend_Controller_Action_Helper_FlashMessenger
     */
    protected $_flashMessenger;
  
    /**
     * __construct() - Instance constructor, needed to get the FlashMessenger
     *
     * @param  void
     * 
     * @return void
     */
    public function __construct()
    {
        $this->_flashMessenger= Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
    }
    
    /**
     * Add an info message
     * 
     * @param string|array $message - The message or messages
     * @param array $actions - OPTIONAL 
     *              an array of url => labels as message actions
     * 
     * @return void 
     */
    public function addInfo($message, $actions = array()) 
    {
        $this->addMessage('info', $message, $actions);
    }
    
    /**
     * Add a success message
     * 
     * @param string|array $message - The message or messages
     * @param array $actions - OPTIONAL 
     *              an array of url => labels as message actions
     * 
     * @return void 
     */
    public function addSuccess($message, $actions = array()) 
    {
        $this->addMessage('success', $message, $actions);
    }
    
    /**
     * Add a warning message
     * 
     * @param string|array $message - The message or messages
     * @param array $actions - OPTIONAL 
     *              an array of url => labels as message actions
     * 
     * @return void 
     */
    public function addWarning($message, $actions = array()) 
    {
        $this->addMessage('warning', $message, $actions);
    }
    
    /**
     * Add a error message
     * 
     * @param string|array $message - The message or messages
     * @param array $actions - OPTIONAL 
     *              an array of url => labels as message actions
     * 
     * @return void 
     */
    public function addError($message, $actions = array()) 
    {
        $this->addMessage('error', $message, $actions);
    }
    
    /**
     * Add a message of a specific type
     * 
     * @param string $type
     * @param string|array $message - The message or messages
     * @param array $actions - OPTIONAL 
     *              an array of url => labels as message actions
     * 
     * @return void
     */
    public function addMessage($type, $message, $actions = array()) 
    {
        $messageArray = array(
            'type'    => $type,
            'message' => $message,
            'actions' => $actions,
        );
        
        $this->_flashMessenger->addMessage($messageArray);
    }
    
    /**
     * Strategy pattern: proxy to addMessage()
     *
     * @param  string $message
     * @return void
     */
    public function direct($type, $message, $actions = array())
    {
        return $this->addMessage($message);
    }
}
