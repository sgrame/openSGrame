<?php

class User_Model_Role
{
    /**
     * Table mapper
     * 
     * @var User_Model_DbTable_Role
     */
    protected $_mapper;
    
    /**
     * "Cached" version of the roles array
     * 
     * RoleID => RoleName
     * 
     * @var array
     */
    protected $_rolesArray;
    

    /**
     * Class constructor
     */
    public function __construct($mapper = null)
    {
        $this->_mapper = (is_null($mapper))
            ? new User_Model_DbTable_Role
            : $mapper;
    }
    
    
    /**
     * Get the role form
     * 
     * @param $role
     * 
     * @return User_Form_Role
     */
    public function getRoleForm($role = null)
    {
        $form = new User_Form_Role();
        
        if($role instanceof User_Model_Row_Role) {
            $data = array(
                'name'     => $role->name,
                'role_id' => $role->id,
            );
            
            $form->populate($data);
        }
        
        return $form;
    }
    
    /**
     * Save the role form
     * 
     * @param User_Form_Role $form
     * 
     * @return User_Model_Row_Role
     */
    public function saveRoleForm(User_Form_Role $form)
    {
        $db = $this->_mapper->getAdapter();
        $db->beginTransaction();
      
        try {
            $role     = $this->_mapper->createRow();
            $values   = $form->getValues();
            
            // update?
            if(!empty($values['role_id'])) {
                $role = $this->findById($values['role_id']);
                if(!$role) {
                    return false;
                }
            }
            
            // Group -----------------------------------------------------------
            $role->name = $values['name'];
            $role->save();
            
            $db->commit();
            return $role;
        }
        catch(Exception $e) {
            $db->rollBack();
            SG_Log::log($e->getMessage(), SG_Log::CRIT);
        }
        
        return false;
    }
    
    /**
     * Role action confirm form proxy
     * 
     * @param string $action
     *     Action to perform
     * @param User_Model_Row_Role $role
     * 
     * @return User_Form_Confirm
     */
    public function getRoleConfirmForm($action, User_Model_Row_Role $role)
    {
        $form = new User_Form_Confirm();
        $form->getElement('id')->setValue((int)$role->id);
        
        $translator = SG_Translator::getInstance();
        
        $legendText = null;
        $noteText   = null;
        $buttonText = null;
        
        switch($action) {
          case 'delete':
              $legendText = $translator->t(
                  'Delete role'
              );
              $noteText = $translator->t(
                  'Are you sure that you want to delete role <strong>%s</strong>?',
                  $role->name
              );
              break;
        }
        
        if($legendText) {
            $form->getDisplayGroup('confirm')->setLegend($legendText);
        }
        if($noteText) {
            $form->getElement('note')->setValue($noteText);
        }
        if($buttonText) {
            $form->getElement('submit')->setLabel($buttonText);
        }
        
        return $form;
    }
    
    
    /**
     * Find a role by its ID
     * 
     * @param int $roleId
     * 
     * @return User_Model_Row_Role
     */
    public function findById($roleId)
    {
        $role = $this->_mapper->find((int)$roleId)->current();
        return $role;
    }
    
    /**
     * Find a role by its name
     * 
     * @param string $name
     * 
     * @return User_Model_Row_Role 
     */
    public function findByName($name)
    {
        $role = $this->_mapper->findByName($name)->current();
        return $role;
    }
    
    /**
     * Get the roles
     * 
     * @param $page
     * @param $order
     * @param $direction
     * @param $search
     * 
     * @return Zend_Paginator_Adapter_DbSelect
     */
    public function getRoles(
        $page = 0, $order = 'name', $direction = 'asc', $search = array()
    )
    {
        $roles  = $this->_mapper->findBySearch($search, $order, $direction);
        $pager  = Zend_Paginator::factory($roles);
        $pager  ->setCurrentPageNumber($page);
        return $pager;
    }
    
    /**
     * Get an array with roleID => roleName
     * 
     * @param bool $excludeSystem
     *     Exclude the system roles (everyone & registered)
     * 
     * @return array 
     */
    public function getRolesAsArray($excludeSystem = true)
    {
        if(is_null($this->_rolesArray)) {
            $this->_rolesArray = array();
            $roles = ($excludeSystem)
              ? $this->_mapper->fetchAllNonSystem()
              : $this->_mapper->fetchAll();
              
            foreach($roles AS $role) {
                $this->_rolesArray[$role->id] = $role->name;
            }
        }
        
        return $this->_rolesArray;
    }
    
    
    /**
     * Get the role id from the role ID or role object
     * 
     * @param mixed $role
     *     The role id or object
     * 
     * @return int
     */
    public static function extractRoleId($role)
    {
        if(is_numeric($role)) {
            return (int)$role;
        }
        
        if($role instanceof User_Model_Row_Role) {
            return (int)$role->id;
        }
        
        throw new Zend_Db_Table_Row_Exception(
            'No valid role ID or role object'
        );
    }
}

