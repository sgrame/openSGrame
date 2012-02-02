<?php
/**
 * Mapper to the User table
 */
class User_Model_DbTable_User extends SG_Db_Table
{
    /**
     * Table name
     * 
     * @var string
     */
    protected $_name = 'user';

    /**
     * The row class name
     * 
     * @var string
     */
    protected $_rowClass = 'User_Model_Row_User';

}

