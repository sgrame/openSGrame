<?php

class User_Model_Permission
{
    /**
     * Permission mapper
     * 
     * @var User_Model_DbTable_Permission
     */
    protected $_mapper;
    
    
    /**
     * Constructor
     * 
     * @param User_Model_DbTable_User $mapper optional mapper
     */
    public function __construct($mapper = null)
    {
        if(!is_null($mapper)) {
            $this->_mapper = $mapper;
        }
        else {
            $this->_mapper = new User_Model_DbTable_Permission();
        }
    }
  
    /**
     * Get the permissions form
     * 
     * @param array $roles
     *     An array (or string) with one ore more role id's
     * 
     * @return User_Model_Permissions
     */
    public function getPermissionsForm($roleFilter = null)
    {
        // prepare
        $translator = SG_Translator::getInstance();
        $form       = new User_Form_Permissions();
        
        // get the roles
        $roleTable  = new User_Model_DbTable_Role();
        $roleFilter = $this->_fetchRoleFilterArray($roleFilter);
        if($roleFilter) {
            $roleRows   = $roleTable->find($roleFilter);
        }
        else {
            $roleRows   = $roleTable->fetchAll();
        }
        
        $roles = array();
        $roleIds   = array();
        foreach($roleRows AS $role) {
            $roles[$role->id] = $role->name;
        }
        $countColumns = count($roles) + 1;
        
        // get the permissions
        $permissions = $this->_mapper->fetchBySearch(
            array(), array('module' => 'ASC', 'name' => 'ASC')
        );
        
        // add the header
        $header = array_merge(array($translator->t('Permission')), array_values($roles));
        $form->setRowHeader($header);

        // add the permissions
        $roleIds = array_keys($roles);
        $lastModule       = null;
        foreach($permissions AS $permission) {
            if($lastModule !== $permission->module) {
                $form->addSeperator(
                    'module_' . $permission->module, 
                    $translator->t($permission->module),
                    $countColumns,
                    'h4'
                );
                $lastModule = $permission->module;
            }
            $form->addRow($permission->name, $permission->id, $roleIds);
        }
        
        // populate the form
        $values = array('permissions' => $this->getPermissionsArray($roleFilter));
        $form->populate($values);
                
        return $form;
    }

    /**
     * Save the permissions form
     * 
     * @param User_Form_Permissions
     * @param string $roleFilter
     *     Filter by given string of possible role id's
     * 
     * @return bool
     *     Success
     */
    public function savePermissionsForm(User_Form_Permissions $form, $roleFilter = null)
    {
        $db = $this->_mapper->getAdapter();
        $db->beginTransaction();
        
        $rolePermissions = new User_Model_DbTable_RolePermissions();
        
        $roleFilter = $this->_fetchRoleFilterArray($roleFilter);
        
        try {
            // get the current permissions
            $old = $this->getPermissionsArray($roleFilter);
            
            // get the form values
            $new = $form->getPermissionValues();
            
            // calculate the diffs
            $add = $this->_diffpermissions($new, $old);
            $del = $this->_diffpermissions($old, $new);
            
            // Add the new permissions
            foreach($add AS $perm => $roles) {
                $permId = (int)preg_replace('/^perm_/', NULL, $perm);
                foreach($roles AS $roleId) {
                    $rolePermissions->createByRoleAndPermission(
                        $roleId, $permId
                    );
                }
            }
            
            // delete the removed permissions
            foreach($del AS $perm => $roles) {
                $permId = (int)preg_replace('/^perm_/', NULL, $perm);
                foreach($roles AS $roleId) {
                    $rolePermissions->deleteByRoleAndPermission(
                        $roleId, $permId
                    );
                }
            }
            
            $db->commit();
            return true;
        }
        catch(Exception $e) {
            $db->rollBack();
            SG_Log::log($e->getMessage(), SG_Log::CRIT);
        }
        
        return false;
    }


    /**
     * Get the permissions array
     * 
     * This is used to populate the permissions form
     * 
     * @param array $roles
     *     Optional array of role ids for who whe get the permissions
     * 
     * @return array
     */
    public function getPermissionsArray($roles = array())
    {
        $permTable = new User_Model_DbTable_RolePermissions();
        $rolePerms = (!empty($roles) && is_array($roles))
            ? $permTable->fetchAllByRoles($roles)
            : $permTable->fetchAll();
        
        $permissions = array();
        foreach($rolePerms AS $rolePerm) {
            $permKey = 'perm_' . $rolePerm->permission_id;
            $roleKey = 'role_' . $rolePerm->role_id;
            if(!isset($permissions[$permKey])) {
                $permissions[$permKey] = array();
            }
            $permissions[$permKey][$roleKey] = $rolePerm->role_id;
        }
        
        return $permissions;
    }
    
    /**
     * Get the Permission id from the Permission ID or Permission object
     * 
     * @param mixed $permission
     *     The Permission id or Permission object
     * 
     * @return int
     */
    public static function extractPermissionId($permission)
    {
        if(is_numeric($permission)) {
            return (int)$permission;
        }
        
        if($permission instanceof User_Model_Row_Permission) {
            return (int)$permission->id;
        }
        
        throw new Zend_Db_Table_Row_Exception(
            'No valid Permission ID or Permission object'
        );
    }
    
    /**
     * Helper to calculate the diff between 2 arrays
     * 
     * @param array $arr1
     * @param array $arr2
     * 
     * @return array
     */
    protected function _diffpermissions($a1, $a2)
    {
        $r = array();
      
        foreach($a1 as $k => $v) {
            //$r[$k] = is_array($v) ? $this->array_diff_key_recursive($a1[$k], $a2[$k]) : array_diff_key($a1, $a2);
            if (is_array($v)) {
                $r[$k] = (isset($a2[$k]))
                    ? $this->_diffpermissions($a1[$k], $a2[$k])
                    : $a1[$k];
                    
                if (is_array($r[$k]) && count($r[$k])==0) {
                    unset($r[$k]);
                }
            }
            else {
                $r = array_diff_key($a1, $a2);
            }
        }
        
        return $r;
    }
    
    /**
     * Helper to get the role id's from a given string
     * 
     * @param string $roleFilter
     * 
     * @return array|null
     */
    protected function _fetchRoleFilterArray($roleFilter)
    {
        if(empty($roleFilter) || !is_string($roleFilter)) {
            return null;
        }

        $roleFilter = explode(',', $roleFilter);
        array_walk($roleFilter, 'trim');
        
        return $roleFilter;
    }
}

