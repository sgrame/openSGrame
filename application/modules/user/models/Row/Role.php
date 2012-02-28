<?php 
/**
 * The role row object
 */
class User_Model_Row_Role extends Zend_Db_Table_Row_Abstract
{
    /**
     * The table class name
     * 
     * @var string
     */
    protected $_tableClass = 'User_Model_DbTable_Role';
    
    /**
     * Count the users (cache)
     * 
     * @var int
     */
    protected $_countUsers = NULL;
    
    /**
     * Get the number of users with this role
     * 
     * @param void
     * 
     * @return int
     */
    public function getUserCount()
    {
        if(is_null($this->_countUsers)) {
            $mapper = new User_Model_DbTable_UserRoles();
            $this->_countUsers = $mapper->countUsersByRole($this->id);
        }
        
        return $this->_countUsers;
    }

    /**
     * Is locked (system) role
     * 
     * @param void
     * 
     * @return bool
     */
    public function isLocked()
    {
        return (bool)$this->locked;
    }
}
