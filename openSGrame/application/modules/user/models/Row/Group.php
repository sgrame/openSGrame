<?php 
/**
 * The group row object
 */
class User_Model_Row_Group extends Zend_Db_Table_Row_Abstract
{
    /**
     * The table class name
     * 
     * @var string
     */
    protected $_tableClass = 'User_Model_DbTable_Group';
    
    /**
     * Count the users (cache)
     * 
     * @var int
     */
    protected $_countUsers = NULL;
    
    /**
     * Get the number of users belonging to this group
     * 
     * @param void
     * 
     * @return int
     */
    public function getUserCount()
    {
        if(is_null($this->_countUsers)) {
            $mapper = new User_Model_DbTable_UserGroups();
            $this->_countUsers = $mapper->countUsersByGroup($this->id);
        }
        
        return $this->_countUsers;
    }

}
