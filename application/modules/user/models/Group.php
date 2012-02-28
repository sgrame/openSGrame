<?php

class User_Model_Group
{
  /**
     * Table mapper
     * 
     * @var User_Model_DbTable_Group
     */
    protected $_mapper;
    
    /**
     * "Cached" version of the roles array
     * 
     * GroupID => GroupName
     * 
     * @var array
     */
    protected $_groupsArray;
    

    /**
     * Class constructor
     */
    public function __construct($mapper = null)
    {
        $this->_mapper = (is_null($mapper))
            ? new User_Model_DbTable_Group
            : $mapper;
    }
    
    /**
     * Get the group form
     * 
     * @param $group
     * 
     * @return User_Form_Group
     */
    public function getGroupForm($group = null)
    {
        $form = new User_Form_Group();
        
        if($group instanceof User_Model_Row_Group) {
            $data = array(
                'name'     => $group->name,
                'group_id' => $group->id,
            );
            
            $form->populate($data);
        }
        
        return $form;
    }
    
    /**
     * Save the group form
     * 
     * @param User_Form_Group $form
     * 
     * @return User_Model_Row_Group
     */
    public function saveGroupForm(User_Form_Group $form)
    {
        $db = $this->_mapper->getAdapter();
        $db->beginTransaction();
      
        try {
            $group    = $this->_mapper->createRow();
            $values   = $form->getValues();
            
            // update?
            if(!empty($values['group_id'])) {
                $group = $this->findById($values['group_id']);
                if(!$group) {
                    return false;
                }
            }
            
            // Group -----------------------------------------------------------
            $group->name = $values['name'];
            $group->save();
            
            $db->commit();
            return $group;
        }
        catch(Exception $e) {
            $db->rollBack();
            SG_Log::log($e->getMessage(), SG_Log::CRIT);
        }
        
        return false;
    }
    
    /**
     * Get the groups
     * 
     * @param $page
     * @param $order
     * @param $direction
     * @param $search
     * 
     * @return Zend_Paginator_Adapter_DbSelect
     */
    public function getGroups(
        $page = 0, $order = 'name', $direction = 'asc', $search = array()
    )
    {
        $acl = Zend_Registry::get('acl');
        if(!$acl->isUserAllowed('user:admin:groups', 'administer system groups')) {
            $search['excludeSystemGroups'] = true;
        }
      
        $groups = $this->_mapper->findBySearch($search, $order, $direction);
        $pager  = Zend_Paginator::factory($groups);
        $pager  ->setCurrentPageNumber($page);
        return $pager;
    }
    
    /**
     * Get an array with groupID => groupName
     * 
     * @param bool $excludeSystem
     *     Exclude the system groups (system)
     * 
     * @return array 
     */
    public function getGroupsAsArray($excludeSystem = true)
    {
        if(is_null($this->_groupsArray)) {
            $this->_groupsArray = array();
            $groups = ($excludeSystem)
                ? $this->_mapper->fetchAllNonSystem()
                : $this->_mapper->fetchAll();
              
            foreach($groups AS $group) {
                $this->_groupsArray[$group->id] = $group->name;
            }
        }
        
        return $this->_groupsArray;
    }


    /**
     * Get the group id from the group ID or group object
     * 
     * @param mixed $group
     *     The group id or object
     * 
     * @return int
     */
    public static function extractGroupId($group)
    {
        if(is_numeric($group)) {
            return (int)$group;
        }
        
        if($group instanceof User_Model_Row_Group) {
            return (int)$group->id;
        }
        
        throw new Zend_Db_Table_Row_Exception(
            'No valid group ID or group object'
        );
    }
}

