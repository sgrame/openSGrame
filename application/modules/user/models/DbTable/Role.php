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
}

