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

}

