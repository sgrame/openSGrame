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
     * @param void
     * 
     * @return User_Model_Permissions
     */
    public function getPermissionsForm()
    {
        // prepare
        $translator = SG_Translator::getInstance();
        $form       = new User_Form_Permissions();
        
        // get the roles
        $roleTable  = new User_Model_DbTable_Role();
        $roleRows   = $roleTable->fetchAll();
        
        $roles = array();
        $roleIds   = array();
        foreach($roleRows AS $role) {
            $roles[$role->id] = $role->name;
        }
        $countColumns = count($roles) + 1;
        
        // get the permissions
        $permissions = $this->_mapper->fetchBySearch();
      
        
        // add the header
        $header = array_merge(array($translator->t('Permission')), array_values($roles));
        $form->setRowHeader($header);

        // add the permissions
        $roleIds = array_keys($roles);
        $lastModule       = null;
        $lastModuleAction = null;
        foreach($permissions AS $permission) {
            $parts = explode(':', $permission->module);
            $moduleName = reset($parts);
            if($lastModule !== $moduleName) {
                $form->addSeperator(
                    'module_' . $moduleName, 
                    $translator->t('%s module', $moduleName),
                    $countColumns,
                    'h3'
                );
                $lastModule = $moduleName;
            }
            if($lastModuleAction !== $permission->module) {
                $form->addSeperator(
                    'action_' . $permission->module, 
                    $translator->t($permission->module),
                    $countColumns,
                    'h4'
                );
                $lastModuleAction = $permission->module;
            }
            $form->addRow($permission->name, $permission->id, $roleIds);
        }
        
        // populate the form
        $values = array('permissions' => $this->getPermissionsArray());
        $form->populate($values);
                
        return $form;
    }

    /**
     * Save the permissions form
     * 
     * @param User_Form_Permissions
     * 
     * @return bool
     *     Success
     */
    public function savePermissionsForm(User_Form_Permissions $form)
    {
        $db = $this->_mapper->getAdapter();
        $db->beginTransaction();
        
        try {
            // get the current permissions
            $prev   = $this->getPermissionsArray();
            
            // get the form values
            $values = $form->getValues();
            $new    = $values['permissions'];
            
            var_dump($prev, $new); die;
            
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
     * @param void
     * 
     * @return array
     */
    public function getPermissionsArray()
    {
        $permTable = new User_Model_DbTable_RolePermissions();
        $rolePerms = $permTable->fetchAll();
        
        $permissions = array();
        foreach($rolePerms AS $rolePerm) {
            $permKey = 'perm_' . $rolePerm->permission_id;
            $roleKey = 'role_' . $rolePerm->role_id;
            if(isset($permissions[$permKey])) {
                $permissions[$permKey] = array();
            }
            $permissions[$permKey][$roleKey] = $rolePerm->role_id;
        }
        
        return $permissions;
    }
}

