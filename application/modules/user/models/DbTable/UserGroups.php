<?php
/**
 * Mapper to the User-Groups table
 */
class User_Model_DbTable_UserGroups extends SG_Db_Table
{
    /**
     * Table name
     */
    protected $_name = 'user_groups';
    
    /**
     * The row class name
     * 
     * @var string
     */
    protected $_rowClass = 'User_Model_Row_UserGroups';
    
    
    /**
     * Create a record
     * 
     * @param mixed $user
     *     User_Model_Row_User or User ID
     * @param mixed $group
     *     User_Model_Row_Group or Group ID
     * 
     * @return User_Model_Row_UserGroups
     */
    public function createByUserAndGroup($user, $group)
    {
        $userGroup = $this->createRow(array(
            'user_id'  => User_Model_User::extractUserId($user),
            'group_id' => User_Model_Group::extractGroupId($group),
        ));
        $userGroup->save();
        
        return $userGroup;
    }
    
    
    /**
     * Delete a record by a given user or user ID
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
     * Delete a record by a given group or group ID
     * 
     * @param mixed $group
     *     User_Model_Row_Group or Group ID
     * 
     * @return bool
     *     Success
     */
    public function deleteByGroup($group)
    {
        $groupId = User_Model_Group::extractGroupId($group);
        $where = $this->getAdapter()->quoteInto('group_id = ?', $groupId);
        return $this->delete($where);
    }
}

