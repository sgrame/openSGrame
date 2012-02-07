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
}

