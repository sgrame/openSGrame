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
     * Fetch all by search
     * 
     * @param array $search
     * @param string $order
     * @param string $direction
     */
    public function findBySearch($search, $order = 'name', $direction = 'asc')
    {
        $select = $this->select();

        if(!empty($search['name'])) {
            $select->where($this->_name . '.name LIKE ?', $search['name']);
        }
        
        $select->order($order . ' ' . strtoupper($direction));

        return $this->fetchAll($select);
    }
    
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
    
    /**
     * Find a role by its name
     * 
     * @param string $name
     * 
     * @return Zend_Db_Table_Rowset
     */
    public function findByName($name)
    {
        $select = $this->select();
        $select ->where('name = ?', $name);
        
        return $this->fetchAll($select);
    }

    /**
     * Check if a role exists with the given name
     * 
     * @param string $name
     * @param int $excludeRoleId
     *     Role id to exclude from the possible matches
     * 
     * @return bool
     */
    public function nameExists($name,  $excludeRoleId = null)
    {
        $roles = $this->findByName($name);
        
        if(!is_null($excludeRoleId)) {
            $excludeRoleId = (int)$excludeRoleId;
        }
        
        foreach($roles AS $role) {
            if((int)$role->id === $excludeRoleId) {
                continue;
            }
            
            return true;
        }
        
        return false;
    }
}

