<?php
/**
 * @category SG
 * @package  View
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_View_Helper_User
 *
 * Helper to access the currently logged in user
 *
 * @category SG
 * @package  View
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_View_Helper_User extends Zend_View_Helper_Abstract 
{
    /**
     * Authentication container
     * 
     * @var Zend_Auth
     */
    protected $_auth;
    
    /**
     * User
     * 
     * Will be a dummy user if not authenticated
     * 
     * @var User_Model_Row_User
     */
    protected $_user;
    
    
    /**
     * Constructor
     * 
     * @param void
     * 
     * @return void
     */
    public function __construct()
    {
        $this->_auth = Zend_Auth::getInstance();
        $this->_user = $this->_auth->getIdentity();
        
        if(!$this->_auth->hasIdentity()) {
            $users = new User_Model_DbTable_User();
            $this->_user = $users->createRow(array(
                'username' => 'Guest',
            ));
        }
    }
  
    /**
     * Get the user object
     *
     * @param void
     * 
     * @return User_Model_Row_User
     */
    public function user()
    {
        return $this->_user;
    }
    
    /**
     * Check logged in
     * 
     * @param void
     * 
     * @return bool
     */
    public function isAuthenticated()
    {
        return $this->_auth->hasIdentity();
    }
}