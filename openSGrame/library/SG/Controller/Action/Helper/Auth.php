<?php
/**
 * @category SG_Controller_Action
 * @package  Helper
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Controller_Action_Helper_Auth
 *
 * Helper to get the currently logged in user
 *
 * @category SG_Controller_Action
 * @package  Helper
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
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
    {}
    
    /**
     * Get identity
     * 
     * @param    void
     * @return    Zend_Auth
     */
    public function getIdentity()
    {
        // check if identity is already set
        if(!empty($this->_identity))
        {
            return $this->_identity; 
        }
        
        // get the auth
        $auth = Zend_Auth::getInstance();
        
        // check if there is an identity
        if (!$auth->hasIdentity()) 
        {
            return false;
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
     * @return    self
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