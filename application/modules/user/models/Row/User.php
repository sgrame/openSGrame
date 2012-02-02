<?php 
/**
 * The user row object
 */
class User_Model_Row_User extends Zend_Db_Table_Row_Abstract
{
    /**
     * The table class name
     * 
     * @var string
     */
    protected $_tableClass = 'User_Model_DbTable_User';
    
    /**
     * Get the locked status
     * 
     * @param void
     * 
     * @return bool
     */
    public function isLocked()
    {
        return (bool)$this->locked;
    }
    
    /**
     * Get the blocked status
     * 
     * @param void
     * 
     * @return bool
     */
    public function isBlocked()
    {
        return (bool)$this->blocked;
    }
    
    /**
     * Method to save the user with status locked = 1
     * 
     * @param void
     * 
     * @return bool    success
     */
    public function lock()
    {
        $this->locked = 1;
        return (bool)$this->save();
    }
    
    /**
     * Method to save the user with status locked = 0
     * 
     * @param void
     * 
     * @return bool    success
     */
    public function unlock()
    {
        $this->locked = 0;
        return (bool)$this->save();
    }
    
    /**
     * Method to save the user with status blocked = 1
     * 
     * @param void
     * 
     * @return bool    success
     */
    public function block()
    {
        $this->blocked = 1;
        return (bool)$this->save();
    }
    
    /**
     * Method to save the user with status blocked = 0
     * 
     * @param void
     * 
     * @return bool    success
     */
    public function unblock()
    {
        $this->blocked = 0;
        return (bool)$this->save();
    }
    
    /**
     * method to check a plain text password against the users 
     * encrypted password
     * 
     * @param string    password
     * 
     * @return bool     valid
     */
    public function checkPassword($_password)
    {
        // check if is modified
        if(isset($this->_modifiedFields['password'])) {
            return $_password === $this->password;
        }
        
        return $this->password === $this->_encryptPassword($_password);
    }
    
    /**
     * Method to create the salted and encrypted password
     * 
     * @param string
     * 
     * @return string
     */
    protected function _encryptPassword($_password)
    {
        return md5(
            $_password
            . $this->password_salt
        );
    }
    
    /**
     * Method to create the salted and encrypted password
     * This will only be done if the password field is modfied
     * 
     * @param string
     * 
     * @return string
     */
    protected function _preSavePassword()
    {
        // check if the current password is modified
        if(!isset($this->_modifiedFields['password'])) {
            return;
        }
        
        // create new password salt
        $token = new SG_Token();
        $this->password_salt = $token->uuid();
        
        $this->password = $this->_encryptPassword($this->password);
    }
    
    /**
     * Pre insert logic
     * This will check if the password needs to be encrypted
     * 
     * @param void
     * @return void
     */
    protected function _insert()
    {
        $this->_preSavePassword();
    }
    
    /**
     * Pre update logic
     * This will check if the password needs to be encrypted
     * 
     * @param void
     * @return void
     */
    protected function _update()
    {
        $this->_preSavePassword();
    }
}
