<?php
/**
 * @category SG
 * @package  Acl
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Acl
 *
 * Database driven ACL implementation
 * Based on @link http://framework.zend.com/wiki/display/ZFUSER/Using+Zend_Acl+with+a+database+backend
 *
 * @category SG
 * @package  Acl
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Acl extends Zend_Acl {
    /**
     * Name of the "everybody" role
     * 
     * @var string
     */
    const ROLE_EVERYONE = 'everyone';
    
    /**
     * Name of the "logged in" role
     * 
     * @var string
     */
    const ROLE_REGISTERED = 'registered';
    
    
    /**
     * Database adapter
     * 
     * @var Zend_Db_Adapter_Abstract
     */
    private $_db;
 
    /**
     * User
     * 
     * @var User_Model_Row_User
     */
    public $_user;
    
 
    /**
     * Constructor 
     *
     * @param User_Model_Row_User
     * @param Zend_Db_Adapter_Abstract
     */
    public function __construct($user = null, $db = null)
    {
        // get the user if none given
        if(!($user instanceof User_Model_Row_User)) {
            $auth = Zend_Auth::getInstance();
            if($auth->hasIdentity()) {
                $user = $auth->getIdentity();
            }
        }
        $this->_user = $user;
        
        // get the db connector
        $this->_db = ($db)
            ? $db
            : Zend_Db_Table::getDefaultAdapter();
        
        // init the roles and permissions
        $this->_init();
    }

    /**
     * Init the ACL
     * 
     * Get the user:
     * - roles
     * - permissions
     */
    protected function _init()
    {
        // get && add the resources
        $dbResources = $this->_getDbResources();
        $resources = array();
        foreach($dbResources AS $resource) {
            $this->addResource(new Zend_Acl_Resource($resource['module']));
        }
        
        // get the roles with their resources
        $dbRoles = $this->_getDbRoles();
        $roles = array();
        $resources = array();
        foreach($dbRoles AS $role) {
            $roleId = $role['id'];
            
            if(!isset($roles[$roleId])) {
                $roles[$roleId] = new Zend_Acl_Role($role['name']);
                $this->addRole($roles[$roleId]);
            }
            
            if(!empty($role['resource']) && !empty($role['privilege'])) {
                $this->allow($role['name'], $role['resource'], $role['privilege']);
            }
        }
        
        // give the admin user all rights
        $admin = $this->_getDbAdminUser();
        $adminRoleName = $this->_getUserRoleName($admin['username']);
        $this->addRole(new Zend_Acl_Role($adminRoleName));
        $this->allow($adminRoleName);
        
        // create a "user" role that inherit from his roles
        $this->_initUser($this->_user);
    }

    /**
     * Check if the current user is allowed
     * 
     * @param string
     *     Resource (module name)
     * @param string
     *     Permission
     * @param User_Model_Row_User
     * 
     * @return bool
     */
    public function isUserAllowed($resource = null, $permission = null, $user = null)
    {
        $role = $this->_getUserRoleName();
        
        if($userRoleName = $this->_initUser($user)) {
            $role = $userRoleName;
        }
        
        return $this->isAllowed(
            $role, 
            $resource, 
            $permission
        );
    }
    
    /**
     * Get the current main user role
     * 
     * @param void
     * 
     * @return string
     */
    public function getCurrentUserRole()
    {
        $role = ($this->_user)
            ? $this->_getUserRoleName()
            : self::ROLE_EVERYONE;
        return $role;
    }
    
    /**
     * Change the currently logged in user
     * 
     * @param User_Model_Row_User
     * 
     * @return void
     */
    public function setCurrentUser(User_Model_Row_User $user)
    {
        $this->_user = $user;
        $this->_initUser($this->_user);
    }
    
    /**
     * Unset the currently logged in user
     * (use only for Unit Testing!)
     * 
     * @param void
     * 
     * @return void
     */
    public function unsetCurrentUser()
    {
        $this->_user = null;
    }
    
    
    
    /**
     * Init the acl for a given user
     * 
     * @param User_Model_Row_User
     * 
     * @return string
     *     The user specific role name (user::[username])
     */
    protected function _initUser($user) {
        if(empty($user)) {
            return;
        }
        
        if(!($user instanceof User_Model_Row_User)) {
            return;
        }
        
        // admin is added to the ACL by default
        if(1 === (int)$user->id) {
            return;
        }
      
        // check not already registered
        $userRoleName = $this->_getUserRoleName($user->username);
        if(in_array($userRoleName, $this->getRoles())) {
            return $userRoleName;
        }
      
        $userRoles = array(self::ROLE_EVERYONE, self::ROLE_REGISTERED);
        $userDbRoles = $this->_getDbUserRoles($user);
            
        foreach($userDbRoles AS $role) {
            $userRoles[] = $role['name'];
        }
        $this->addRole(
            new Zend_Acl_Role($userRoleName), 
            $userRoles
        );
        
        return $userRoleName;
    }
    
    /**
     * Get the string representing the user role name
     * 
     * Will return user::[username]
     * 
     * @param string $username
     *     (optional name)
     * 
     * @return string
     */
    protected function _getUserRoleName($username = null)
    {
        $role = ($this->_user)
            ? 'user::' . $this->_user->username
            : self::ROLE_EVERYONE;
        
        if(!is_null($username)) {
            $role = 'user::' . $username;
        }
                
        return $role;
    }
    
    /**
     * Get the admin user (user id = 1)
     * 
     * @param void
     * 
     * @return object
     */
    protected function _getDbAdminUser()
    {
        $q = $this->_db->select();
        
        $q->from('user')
          ->where('id = 1');
        
        return $this->_db->query($q)->fetch();
    }
    
    /**
     * Get the roles and their resource:permissions id from the user tables
     * 
     * @param void
     * 
     * @return Zend_Db_Statement
     */
    protected function _getDbRoles()
    {
        // resources
        $q = $this->_db->select();
        $q->from(
            array('ur' => 'user_role'), 
            array('ur.id', 'ur.name')
          )
          ->joinLeft(
              array('urp' => 'user_role_permissions'),
              'ur.id = urp.role_id',
              array()
          )
          ->joinLeft(
              array('up' => 'user_permission'),
              'urp.permission_id = up.id',
              array(
                  'resource' => 'up.module', 
                  'privilege' => 'up.name'
              )
          );
        
        $q->where('ur.cr IS NULL')
          ->where('urp.cr IS NULL')
          ->where('up.cr IS NULL');
          
        $q->order('ur.name')
          ->order('up.module')
          ->order('up.name');
          
        $roles = $this->_db->query($q)->fetchAll();
        return $roles;
    }
    
    /**
     * Get the resources from the user tables
     * 
     * @param void
     * 
     * @return Zend_Db_Statement
     */
    protected function _getDbResources()
    {
        // resources
        $q = $this->_db->select()->distinct();
        $q->from(
              array('up' => 'user_permission'), 
              array('up.module')
          )
          ->where('up.cr IS NULL')
          ->group('up.module')
          ->order('up.module');
         
        $resources = $this->_db->query($q)->fetchAll();
        return $resources;
    }
    
    /**
     * Get the current user roles
     * 
     * @param void
     * 
     * @return Zend_Db_Statement
     */
    protected function _getDbUserRoles($user)
    {
        $q = $this->_db->select();
        $q->from(
            array('ur' => 'user_roles'),
            array()
          )
          ->join(
            array('r' => 'user_role'),
            'ur.role_id = r.id',
            array('r.name')
          );
        $q->where('ur.cr IS NULL')
          ->where('r.cr IS NULL')
          ->where('ur.user_id = ?', (int)$user->id);
        $q->order('r.name');
        
        $roles = $this->_db->query($q)->fetchAll();
        return $roles;
    }
}