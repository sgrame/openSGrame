<?php
/**
 * Mapper to the Role-Rights table
 */
class User_Model_DbTable_RolePermissions extends SG_Db_Table
{
    /**
     * Table name
     */
    protected $_name = 'user_role_permissions';
    
    /**
     * The row class name
     * 
     * @var string
     */
    protected $_rowClass = 'User_Model_Row_RolePermissions';
    
    /**
     * Fetch all by given role ids
     * 
     * @param array $roles
     *     (optional) array of role id's
     * 
     * @return User_Model_DbTable_RolePermissions
     */
    public function fetchAllByRoles($roles) {
        $select = $this->select();
        $select->where('role_id IN (?)', $roles);
        return $this->fetchAll($select);
    }
    
    /**
     * Create a record
     * 
     * @param mixed $role
     *     User_Model_Row_Role or Role ID
     * @param mixed $permission
     *     User_Model_Row_Permission or Permission ID
     * 
     * @return User_Model_Row_RolePermission
     */
    public function createByRoleAndPermission($role, $permission)
    {
        $rolePerm = $this->createRow(array(
            'role_id'       => User_Model_Role::extractRoleId($role),
            'permission_id' => User_Model_Permission::extractPermissionId($permission),
        ));
        $rolePerm->save();
        
        return $rolePerm;
    }
    
    /**
     * Delete a record
     * 
     * @param mixed $role
     *     User_Model_Row_Role or Role ID
     * @param mixed $permission
     *     User_Model_Row_Permission or Permission ID
     * 
     * @return bool
     */
    public function deleteByRoleAndPermission($role, $permission)
    {
        $roleId = User_Model_Role::extractRoleId($role);
        $permId = User_Model_Permission::extractPermissionId($permission);
        
        $where = array();
        $where[] = $this->getAdapter()->quoteInto('role_id = ?', $roleId);
        $where[] = $this->getAdapter()->quoteInto('permission_id = ?', $permId);
        
        return $this->delete($where);
    }
    
    
    /**
     * Delete all records by a given Role or Role ID
     * 
     * @param mixed $role
     *     User_Model_Row_Role or Role ID
     * 
     * @return bool
     *     Success
     */
    public function deleteByRole($role)
    {
        $roleId = User_Model_Role::extractRoleId($role);
        $where = $this->getAdapter()->quoteInto('role_id = ?', $roleId);
        return $this->delete($where);
    }
    
    /**
     * Delete all records by a given Permission or Permission ID
     * 
     * @param mixed $permission
     *     User_Model_Row_Permission or Permission ID
     * 
     * @return bool
     *     Success
     */
    public function deleteByUser($permission)
    {
        $permId = User_Model_Permission::extractPermissionId($permission);
        $where = $this->getAdapter()->quoteInto('permission_id = ?', $permId);
        return $this->delete($where);
    }
}

