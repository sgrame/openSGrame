<?php
/**
 * Mapper to the Role table
 */
class User_Model_DbTable_Role extends SG_Db_Table
{
    /**
     * Table name
     */
    protected $_name = 'user_role';

    /**
     * The row class name
     * 
     * @var string
     */
    protected $_rowClass = 'User_Model_Row_Role';
    
    /**
     * Get all the roles EXLUSIVE the system roles
     * 
     * @param void
     * 
     * @return Zend_Db_Table_Rowset
     */
    public function fetchAllNonSystem($select = null) {
        if (!($select instanceof Zend_Db_Table_Select)) {
            $select = $this->select();
        }
        
        $select->where($this->_name . '.cr IS NULL');
        $select->where($this->_name . '.id > 2');
        
        return $this->fetchAll($select);
    }
    
    /**
     * Fetch all roles for a given user
     * 
     * @param mixed $user
     *     Or the user id or the user object
     * 
     * @return Zend_Db_Table_Rowset
     */
    public function getAllByUser($user)
    {
        $userId = User_Model_User::extractUserId($user);
      
        $select = $this->select();
        /* @var $select Zend_Db_Select */
        $select
            ->from($this->_name)
            ->setIntegrityCheck(false)
            ->join(
                'user_roles', 
                $this->_name.'.id = user_roles.role_id', 
                array()
            )
            ->where('user_roles.user_id = ?', $userId)
            ->where($this->_name . '.cr IS NULL')
            ->where('user_roles.cr IS NULL');
          
        return $this->fetchAll($select);
    }
    
}

