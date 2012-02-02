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
}

