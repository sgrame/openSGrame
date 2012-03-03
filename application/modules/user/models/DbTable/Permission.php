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
    
    /**
     * Fetch all by search
     * 
     * @param array $search
     * @param array $order
     *     'fieldname' => 'direction' (ASC/DESC)
     * 
     * @return  Zend_Db_Table_Rowset
     */
    public function fetchBySearch($search = array(), $order = array())
    {
        $select = $this->select();

        foreach($order AS $field => $direction) {
            $select->order($field . ' ' . strtoupper($direction));
        }
        
        return $this->fetchAll($select);
    }
    
}

