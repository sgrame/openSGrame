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
        $auth = Zend_Auth::getInstance();
        $this->_user = $auth->getIdentity();
        
        if(!$auth->hasIdentity()) {
            $users = new User_Model_DbTable_User();
            $this->_user = $users->createRow(array(
                'username' => 'Guest',
            ));
        }
    }
  
    /**
     * Get the user object
     *
     * @return User_Model_Row_User
     */
    public function user()
    {
        return $this->_user;
    }
}