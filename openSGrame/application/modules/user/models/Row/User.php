<?php 
/**
 * The user row object
 */
class User_Model_Row_User extends Zend_Db_Table_Row_Abstract
{
    /**
     * Store the user groups
     * 
     * @var Zend_Db_Table_Rowset
     */
    protected $_groups;
    
    /**
     * Store the user roles
     * 
     * @var Zend_Db_Table_Rowset
     */
    protected $_roles;
    
  
    /**
     * The table class name
     * 
     * @var string
     */
    protected $_tableClass = 'User_Model_DbTable_User';
    
    /**
     * Get the users fullname
     * 
     * This will return the firstname fullname.
     * If none of them are set, the username will be returned.
     * 
     * @param void
     * 
     * @return string 
     */
    public function getFullName()
    {
        $name = array();
        
        if (!empty($this->firstname)) {
            $name[] = $this->firstname;
        }
        if (!empty($this->lastname)) {
            $name[] = $this->lastname;
        }
        
        if (empty($name)) {
            $name[] = $this->username;
        }
        
        return implode(' ', $name);
    }
    
    /**
     * Check if user is not blocked or locked
     * 
     * @param void
     * 
     * @return bool
     */
    public function isActive()
    {
        return (!$this->isBlocked() && !$this->isLocked());
    }
    
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
     * Activate a user
     * 
     * @param void
     * 
     * @return bool
     *     success
     */
    public function activate()
    {
        $this->locked = 0;
        $this->blocked = 0;
        return (bool)$this->save();
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
     * Get the group names as an array
     * 
     * @param void
     * 
     * @return array
     */
    public function getGroupNames()
    {
        $groupRecords = $this->getGroups();
        
        $groups = array();
        foreach($groupRecords AS $group) {
            $groups[$group->id] = $group->name;
        }
        
        return $groups;
    }
    
    /**
     * Get the user groups
     * 
     * @param void
     * 
     * @return Zend_Db_Table_Rowset
     */
    public function getGroups()
    {
        if(!$this->_groups) {
            $table = new User_Model_DbTable_Group();
            $this->_groups = $table->getAllByUser($this);
        }
        
        return $this->_groups;
    }
    
    /**
     * Check if given group is one of the users groups
     * 
     * @param mixed $group
     *      Group Id or User_Model_Row_Group object
     * 
     * @return bool 
     */
    public function isMemberOfGroup($group)
    {
        $groupId = (is_numeric($group))
            ? (int)$group
            : (int)$group->id;
        unset($group);
        
        $groups = $this->getGroups();
        foreach ($groups AS $group) {
            if ((int)$group->id === $groupId) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get the role names as an array
     * 
     * Array contains:
     *   roleId => roleName
     * 
     * @param void
     * 
     * @return array
     */
    public function getRoleNames()
    {
        $roleRecords = $this->getRoles();
        
        $roles = array();
        foreach($roleRecords AS $role) {
            $roles[$role->id] = $role->name;
        }
        
        return $roles;
    }
    
    /**
     * Get the user roles
     * 
     * @param void
     * 
     * @return Zend_Db_Table_Rowset
     */
    public function getRoles()
    {
        if(!$this->_roles) {
            $table = new User_Model_DbTable_Role();
            $this->_roles = $table->getAllByUser($this);
        }
        
        return $this->_roles;
    }
    
    /**
     * Check if the user has specific role
     * 
     * @param mixed $role
     *     Role object or role ID
     * 
     * @return bool 
     */
    public function hasRole($role)
    {
        $roleId = User_Model_Role::extractRoleId($role);
        
        $roles = $this->getRoles();
        
        foreach ($roles AS $role) {
            if ($roleId === (int)$role->id) {
                return true;
            }
        }
        
        return false;
    }


    /**
     * Pre save the username
     * 
     * Check if the given username doesn't already exists for another user
     * 
     * @param void
     * 
     * @return void
     * @throws Zend_Db_Table_Row_Exception
     */
    protected function _preSaveUsername()
    {
        if(!isset($this->_modifiedFields['username']) 
            || !$this->_modifiedFields['username']
        ) {
            return;
        }
        $userId = (empty($this->_cleanData['id']))
            ? NULL
            : (int)$this->_cleanData['id'];
            
        if($this->_table->usernameExists(
            $this->_data['username'],
            $userId
        )) {
            throw new Zend_Db_Table_Row_Exception(
                'Username already exists for other user'
            );
        }
    }
    
    /**
     * Pre save the email address
     * 
     * Check if the given email address doesn't already exists for another user
     * 
     * @param void
     * 
     * @return void
     * @throws Zend_Db_Table_Row_Exception
     */
    protected function _preSaveEmail()
    {
        if(!isset($this->_modifiedFields['email']) 
            || !$this->_modifiedFields['email']
        ) {
            return;
        }
        $userId = (empty($this->_cleanData['id']))
            ? NULL
            : (int)$this->_cleanData['id'];
            
        if($this->_table->emailExists(
            $this->_data['email'],
            $userId
        )) {
            throw new Zend_Db_Table_Row_Exception(
                'Email address already in use for other user'
            );
        }
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
        $this->_preSaveUsername();
        $this->_preSaveEmail();
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
        $this->_preSaveUsername();
        $this->_preSaveEmail();
    }
}

