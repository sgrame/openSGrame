<?php
/**
 * Mapper to the Right table
 */
class User_Model_DbTable_Permission extends SG_Db_Table
{
    /**
     * Table name
     */
    protected $_name = 'user_permission';

    /**
     * The row class name
     * 
     * @var string
     */
    protected $_rowClass = 'User_Model_Row_Permission';
}

