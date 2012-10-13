<?php
/**
 * @category Activity
 * @package  Model
 * @author   Peter Decuyper <peter@serial-graphics.be>
 * @filesource
 */


/**
 * Activity_Model_DbTable_Activity
 *
 * Activity table
 *
 * @category Candidate
 * @package  Model
 * @author   Peter Decuyper <peter@serial-graphics.be>
 */
class Activity_Model_DbTable_Activity extends SG_Db_Table
{
    /**
     * Table name
     * 
     * @var string
     */
    protected $_name = 'activity';

    /**
     * The row class name
     * 
     * @var string
     */
    protected $_rowClass = 'Activity_Model_Row_Activity';
    
    /**
     * CR on
     * 
     * @var   bool
     */
    protected $_contingency = true;
    
    
    /**
     * Find activities by search params
     * 
     * The following search parameters are supported:
     *   - users        : alias for owners
     *   - owners       : OR User id
     *                    OR USer_Model_Row_User
     *                    OR Array of user id's and/or User_Model_Row_User objects (may be mixed)
     *                    OR Zend_Db_Table_Rowset
     *   - groups       : OR Group id
     *                    OR User_Model_Row_Group
     *                    OR Array of group id's and/or User_Model_Row_Group objects (may be mixed)
     *                    OR Zend_Db_Table_Rowset
     *   - modules      : string OR array of module name(s)
     *   - types        : string OR array of type(s)
     *   - module:types : string OR array of "module:type" strings
     * 
     * @param array $search
     * @param array $order
     *     ($field => $direction)
     * 
     * @return Zend_Db_Table_Rowset
     */
    public function findBySearch($search = array(), $order = array())
    {
        $select = $this->select();
        /* @var $select Zend_Db_Select */
        $select->from($this->_name, '*');
        $select->where($this->_name . '.cr IS NULL');
        
        // we allow users as alias for owners
        if (isset($search['users'])) {
            $this->_whereOwners($select, $search['users']); 
        }
        
        // search by one or more owners
        if (isset($search['owners'])) {
            $this->_whereOwners($select, $search['owners']); 
        }
        
        // search by one or more groups
        if (isset($search['groups'])) {
            $this->_whereGroups($select, $search['groups']);
        }
        
        // search by module name(s)
        if (isset($search['modules'])) {
            $this->_whereModules($select, $search['modules']);
        }
        
        // search by type
        if (isset($search['types'])) {
            $this->_whereTypes($select, $search['types']);
        }
        
        // search by module-type
        if (isset($search['module:types'])) {
            $this->_whereModuleTypes($select, $search['module:types']);
        }
        
        if (empty($order)) {
            $order['created'] = 'DESC';
        }
        foreach($order AS $field => $direction) {
            $select->order($field . ' ' . strtoupper($direction));
        }
        
        return $this->fetchAll($select);
    }
    
    
    
    /**
     * Helper to add a search by owner(s)
     * 
     * @param Zend_Db_Select $select
     * @param int|User_Model_User|array $owners
     *     OR User id
     *     OR USer_Model_Row_User
     *     OR Array of user id's and/or User_Model_Row_User objects (may be mixed)
     *     OR Zend_Db_Table_Rowset
     * 
     * @return void
     */
    protected function _whereOwners($select, $owners)
    {
        if (!is_array($owners) 
            && !($owners instanceof Zend_Db_Table_Rowset)
        ) {
            $select->where(
                $this->_name . '.owner_id = ?', 
                User_Model_User::extractUserId($owners)
            );
        }
        else {    
            $ownerIds = array();
            foreach($owners AS $owner) {
                $ownerIds[] = User_Model_User::extractUserId($owner);
            }
            $select->where('owner_id IN (?)', $ownerIds);
        }
    }
    
    /**
     * Helper to add a search by group(s)
     * 
     * @param Zend_Db_Select $select
     * @param int|User_Model_Group|array $groups
     *     OR Group id
     *     OR User_Model_Row_Group
     *     OR Array of group id's and/or User_Model_Row_Group objects (may be mixed)
     *     OR Zend_Db_Table_Rowset
     * 
     * @return void
     */
    protected function _whereGroups($select, $groups) 
    {
        // join with the user-groups table
        $select->join(
            'user_groups', 
            $this->_name . '.owner_id = user_groups.user_id', 
            array()
        );
        $select->where('user_groups.cr IS NULL');
        
        if (!is_array($groups) 
            && 
            !($groups instanceof Zend_Db_Table_Rowset)
        ) {
            $select->where(
                'user_groups.group_id = ?', 
                User_Model_Group::extractGroupId($groups)
            );
        }
        else {
            $groupIds = array();
            foreach($groups AS $group) {
                $groupIds[] = User_Model_Group::extractGroupId($group);
            }

            $select->where('user_groups.group_id IN (?)', $groupIds);
        }
    }
    
    /**
     * Helper to add a search by modules(s)
     * 
     * @param Zend_Db_Select
     * @param string|array $modules
     * 
     * @return void
     */
    protected function _whereModules($select, $modules) 
    {
        if (!is_array($modules)) {
            $select->where($this->_name . '.module = ?', $modules);
        }
        else {
            $select->where($this->_name . '.module IN (?)', $modules);
        }
    }
    
    /**
     * Helper to add a search by type(s)
     * 
     * @param Zend_Db_Select
     * @param string|array $types
     * 
     * @return void
     */
    protected function _whereTypes($select, $types) 
    {
        if (!is_array($types)) {
            $select->where($this->_name . '.type = ?', $types);
        }
        else {
            $select->where($this->_name . '.type IN (?)', $types);
        }
    }
    
    /**
     * Helper to add a search by module:type to a select query
     * 
     * @param Zend_Db_Select $select
     * @param string|array $moduleTypes
     *     string or array in the format "module:type"
     * 
     * @return void
     */
    protected function _whereModuleTypes($select, $moduleTypes)
    {
        if (empty($moduleTypes)) {
            return;
        }
        
        if (!is_array($moduleTypes)) {
            $moduleTypes = array($moduleTypes);
        }
        
        $db     = $this->getAdapter();
        $wheres = array();
        $args   = array();
        foreach($moduleTypes AS $moduleType) {
            list($module, $type) = explode(':', $moduleType, 2);
            $wheres[] = 
                '('
                . $db->quoteInto($this->_name . '.module = ?', $module)
                . ' AND '
                . $db->quoteInto($this->_name . '.type = ?', $type)
                . ')';
        }
        
        $select->where(implode(' OR ', $wheres), $args);
    }
}

