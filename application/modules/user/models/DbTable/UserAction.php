<?php
/**
 * Mapper to the User Action table
 */
class User_Model_DbTable_UserAction extends SG_Db_Table
{
    /**
     * Table name
     */
    protected $_name = 'user_action';
    
    /**
     * The row class name
     * 
     * @var string
     */
    protected $_rowClass = 'User_Model_Row_UserAction';
    
    /**
     * Get registered action(s) for a user
     * 
     * @param int
     *     The user id
     * @param string
     *     The action realm
     * 
     * @return User_Model_Row_UserAction
     */
    public function findByUserId($userId, $action = null)
    {
        $select = $this->select();
        /* @var $q Zend_Db_Table_Select */
        $select->where('user_id = ?', (int)$userId);
        
        if(!is_null($action)) {
            $select->where('action = ?', $action);
        }
        
        return $this->fetchAll($select);
    }
    
    /**
     * Get an user action by its Universal Unique ID (UUID)
     * 
     * @param string
     * 
     * @return User_Model_Row_UserAction
     */
    public function findByUuid($uuid)
    {
        $select = $this->select();
        /* @var $q Zend_Db_Table_Select */
        $select->where('uuid = ?', $uuid);
        
        return $this->fetchAll($select);
    }
}

