<?php
/**
 * @category User
 * @package  Model
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * User_Model_Auth
 *
 * User authentication model
 *
 * @category User
 * @package  Model
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_Model_Auth
{
    /**
     * Get the authentication object
     * 
     * @param void
     * 
     * @return Zend_Auth
     */
    public function getAuthObject()
    {
        return Zend_Auth::getInstance();
    }
    
    /**
     * Get the login form
     * 
     * @param array
     * 
     * @return User_Form_Auth
     */
    public function getAuthForm($_data = array())
    {
        $form = new User_Form_Login();
        if(isset($_data['password']))
        {
            unset($_data['password']);
        }
        $form->populate($_data);
        
        return $form;
    }
    
    /**
     * Is there an authenticated user
     * 
     * @param void
     * 
     * @return bool
     */
    public function hasAuthenticatedUser()
    {
        $auth = $this->getAuthObject();
        return $auth->hasIdentity();
    }
    
    /**
     * Get the current user
     * 
     * @param void
     * 
     * @return User_Model_User_Row
     */
    public function getAuthenticatedUser()
    {
        if(!$this->hasAuthenticatedUser()) {
            return false;
        }
        
        return $this->getAuthObject()->getIdentity();
    }
    
    /**
     * Authenticate user
     * 
     * @param User_Form_Auth
     * 
     * @return bool
     *     success
     */
    public function authenticateForm(Zend_Form $_form)
    {
        $mapper = new User_Model_DbTable_User();
        $user = $mapper->findByUsername($_form->getValue('username'))->current();
        /* @var $user User_Model_User */
        
        // check if user is found
        if(!$user) {
            return false;
        }
        
        // check the password
        if(!$user->checkPassword($_form->getValue('password'))) {
            return false;
        }
        
        // check if not locked
        if($user->isLocked()) {
            return false;
        }
        
        // check if not blocked
        if($user->isBlocked()) {
            return false;
        }
        
        // store the authenticated user
        $this->authenticateUser($user);
        
        // authenticated
        return true;
    }
    
    /**
     * Authenticate given user
     * 
     * @param User_Model_Row_User
     * 
     * @return bool
     *     Success
     */
    public function authenticateUser(User_Model_Row_User $user)
    {
        $auth = $this->getAuthObject();
        $auth->getStorage()->write($user);
        
        return true;
    }
    
    /**
     * Unset the identity from the auth object
     */
    public function unsetAuth()
    {
        // get the identity
        $this->getAuthObject()->clearIdentity();
    }
}