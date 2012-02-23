<?php

class User_Model_Role
{
    /**
     * Table mapper
     * 
     * @var User_Model_DbTable_Role
     */
    protected $_mapper;
    
    /**
     * "Cached" version of the roles array
     * 
     * RoleID => RoleName
     * 
     * @var array
     */
    protected $_rolesArray;
    

    /**
     * Class constructor
     */
    public function __construct($mapper = null)
    {
        $this->_mapper = (is_null($mapper))
            ? new User_Model_DbTable_Role
            : $mapper;
    }
    
    /**
     * Get an array with roleID => roleName
     * 
     * @param bool $excludeSystem
     *     Exclude the system roles (everyone & registered)
     * 
     * @return array 
     */
    public function getRolesAsArray($excludeSystem = true)
    {
        if(is_null($this->_rolesArray)) {
            $this->_rolesArray = array();
            $roles = ($excludeSystem)
              ? $this->_mapper->fetchAllNonSystem()
              : $this->_mapper->fetchAll();
              
            foreach($roles AS $role) {
                $this->_rolesArray[$role->id] = $role->name;
            }
        }
        
        return $this->_rolesArray;
    }
}

