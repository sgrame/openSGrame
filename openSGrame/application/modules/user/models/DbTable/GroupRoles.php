<?php
/**
 * Mapper to the Group-Roles table
 */
class User_Model_DbTable_GroupRoles extends SG_Db_Table
{
    /**
     * Table name
     */
    protected $_name = 'user_group_roles';
    
    /**
     * The row class name
     * 
     * @var string
     */
    protected $_rowClass = 'User_Model_Row_GroupRoles';
}

