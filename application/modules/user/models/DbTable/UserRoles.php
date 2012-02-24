<?php
/**
 * Mapper to the Roles table
 */
class User_Model_DbTable_UserRoles extends SG_Db_Table
{
    /**
     * Table name
     */
    protected $_name = 'user_roles';
    
    /**
     * The row class name
     * 
     * @var string
     */
    protected $_rowClass = 'User_Model_Row_UserRoles';
    
    
    /**
     * Create a record
     * 
     * @param mixed $user
     *     User_Model_Row_User or User ID
     * @param mixed $role
     *     User_Model_Row_Role or Role ID
     * 
     * @return User_Model_Row_UserRoles
     */
    public function createByUserAndRole($user, $role)
    {
        $userRole = $this->createRow(array(
            'user_id' => User_Model_User::extractUserId($user),
            'role_id' => User_Model_Role::extractRoleId($role),
        ));
        $userRole->save();
        
        return $userRole;
    }
    
    
    /**
     * Delete a record by a given User or User ID
     * 
     * @param mixed $user
     *     User_Model_Row_User or User ID
     * 
     * @return bool
     *     Success
     */
    public function deleteByUser($user)
    {
        $userId = User_Model_User::extractUserId($user);
        $where = $this->getAdapter()->quoteInto('user_id = ?', $userId);
        return $this->delete($where);
    }
    
    /**
     * Delete a record by a given Role or Role ID
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
}

