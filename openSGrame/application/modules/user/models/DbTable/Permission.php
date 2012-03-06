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
        $select->from($this->_name, '*');
        
        if(!empty($search['roles']) && is_array($search['roles'])) {
            $select->join('user_role_permissions', $this->_name . '.id = user_role_permissions.permission_id', array())
                   ->join('user_role', 'user_role_permissions.role_id = user_role.id', array());
            $select->where('user_role.id IN (?)', $search['roles']);
            $select->where($this->_name . '.cr IS NULL')
                   ->where('user_role_permissions.cr IS NULL')
                   ->where('user_role.cr IS NULL');
        }

        foreach($order AS $field => $direction) {
            $select->order($field . ' ' . strtoupper($direction));
        }
        
        return $this->fetchAll($select);
    }
    
}

