<?php
/**
 * @group SG
 */
class SG_AclTest extends PHPUnit_Framework_TestCase
{
    /**
     * User names & password
     */
    protected $_users = array(
        1 => array(
            'username' => 'SG_AclTest_User1_Name',
            'password' => 'SG_AclTest_User1_Pass',
        ),
        2 => array(
            'username' => 'SG_AclTest_User2_Name', 
            'password' => 'SG_AclTest_User2_Pass',
        ),
    );
    
    /**
     * Roles
     */
    protected $_roles = array(
        1 => array('name' => 'SG_AclTest_Role1'),
        2 => array('name' => 'SG_AclTest_Role2'),
    );
    
    /**
     * Permissions
     */
    protected $_permissions = array(
        'SG_AclTest_Module1' => array(
            'view', 'create', 'edit', 'delete',
        ),
        'SG_AclTest_Module2' => array(
            'view', 'create', 'edit', 'delete',
        ),
    );
    
    /**
     * Users mapper
     * 
     * User_Model_DbTable_User
     */
    protected $_userMapper;
    
  
    /**
     * setUp
     */
    public function setUp()
    {
        $this->_cleanUpUserTables();
        $this->_setUpUserTables();
        $this->_userMapper = new User_Model_DbTable_User();
    }
    
    /**
     * tearDown
     */
    public function tearDown()
    {
        $this->_cleanUpUserTables();
    }
  
    
    /**
     * Empty test
     */
    public function _testAdminUser()
    {
        $admin = $this->_userMapper->find(1)->current();
        $acl = new SG_Acl($admin);
        
        $this->assertTrue($acl->isUserAllowed());
        $this->assertTrue($acl->isUserAllowed('SG_AclTest_Module1'));
        $this->assertTrue($acl->isUserAllowed('SG_AclTest_Module2'));
    }
    
    /**
     * Test users
     */
    public function testUser()
    {
        $userName1 = $this->_users[1]['username'];
        $user1 = $this->_userMapper->findByUsername($userName1)->current();
        
        $acl = new SG_Acl($user1);
        
        $this->assertTrue($acl->isUserAllowed('SG_AclTest_Module1'));
        $this->assertTrue($acl->isUserAllowed('SG_AclTest_Module1', 'view'));
        $this->assertTrue($acl->isUserAllowed('SG_AclTest_Module1', 'create'));
        $this->assertTrue($acl->isUserAllowed('SG_AclTest_Module1', 'edit'));
        $this->assertTrue($acl->isUserAllowed('SG_AclTest_Module1', 'delete'));
        $this->assertFalse($acl->isUserAllowed('SG_AclTest_Module2'));
        
        // test other user
        $userName2 = $this->_users[2]['username'];
        $user2 = $this->_userMapper->findByUsername($userName2)->current();
        $this->assertFalse($acl->isUserAllowed('SG_AclTest_Module1', null, $user2));
        $this->assertFalse($acl->isUserAllowed('SG_AclTest_Module1', 'view', $user2));
        $this->assertTrue($acl->isUserAllowed('SG_AclTest_Module2', null, $user2));
        $this->assertTrue($acl->isUserAllowed('SG_AclTest_Module2', 'view', $user2));
    }
    
    /**
     * Test the get current username
     */
    public function testGetCurrentUserRole() {
        $userName1 = $this->_users[1]['username'];
        $user1 = $this->_userMapper->findByUsername($userName1)->current();
        
        $acl = new SG_Acl($user1);
        
        $this->assertEquals('user::' . $userName1, $acl->getCurrentUserRole());
    }
    
    /**
     * Test changing the current user
     */
    public function testSetCurrentUser() {
        $userName1 = $this->_users[1]['username'];
        $user1 = $this->_userMapper->findByUsername($userName1)->current();
        
        $acl = new SG_Acl($user1);
        $this->assertEquals('user::' . $userName1, $acl->getCurrentUserRole());
        
        // change
        $userName2 = $this->_users[2]['username'];
        $user2 = $this->_userMapper->findByUsername($userName2)->current();
        $acl->setCurrentUser($user2);
        $this->assertEquals('user::' . $userName2, $acl->getCurrentUserRole());
    }
    
    
    
    
    /**
     * Prepare some testing data
     */
    protected function _setUpUserTables()
    {
        // create users, roles and permissions
        $users = new User_Model_DbTable_User();
        $usersTest = array();
        foreach($this->_users AS $key => $data) {
            $user = $users->createRow($data);
            $user->save();
            $usersTest[$key] = $user;
        }
        
        $roles = new User_Model_DbTable_Role();
        $rolesTest = array();
        foreach($this->_roles AS $key => $data) {
            $role = $roles->createRow($data);
            $role->save();
            $rolesTest[$key] = $role;
        }
        
        $permissions = new User_Model_DbTable_Permission();
        $permsTest = array();
        foreach($this->_permissions AS $module => $perms) {
            $permsTest[$module] = array();
            foreach($perms AS $perm) {
                $dbPerm = $permissions->createRow(array(
                    'module' => $module,
                    'name'   => $perm,
                ));
                $dbPerm->save();
                $permsTest[$module][$perm] = $dbPerm;
            }
        }
        
        // give some permissions to roles
        $rolePermissions = new User_Model_DbTable_RolePermissions();
        $i = 1;
        foreach($permsTest AS $name => $perms) {
            $role = $rolesTest[$i];
            foreach($perms AS $perm) {
                $rolePerm = $rolePermissions->createRow(array(
                    'role_id'       => $role->id,
                    'permission_id' => $perm->id,
                ))->save();
            }
            $i++;
        }
        
        // give some roles to users
        $userRoles = new User_Model_DbTable_UserRoles();
        foreach($usersTest AS $key => $userTest) {
            $role = $rolesTest[$key];
            $userRoles->createRow(array(
                'user_id' => $userTest->id,
                'role_id' => $role->id,
            ))->save();
        }
    }
    
    
    /**
     * Cleanup the test data
     */
    protected function _cleanUpUserTables()
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        
        // cleanup permissions
        $q = array();
        $q[] = 'DELETE up.*, urp.*';
        $q[] = 'FROM';
        $q[] = '    user_permission up';
        $q[] = '    INNER JOIN user_role_permissions urp ON up.id = urp.permission_id';
        $q[] = 'WHERE';
        $q[] = '    up.module LIKE "SG_AclTest_Module%";';
        $q = implode(PHP_EOL, $q);
        $db->query($q);
        
        // cleanup roles
        $q = array();
        $q[] = 'DELETE ur.*, uur.*';
        $q[] = 'FROM';
        $q[] = '    user_role ur';
        $q[] = '    INNER JOIN user_roles uur ON ur.id = role_id';
        $q[] = 'WHERE';
        $q[] = '    ur.name LIKE "SG_AclTest_Role%";';
        $q = implode(PHP_EOL, $q);
        $db->query($q);
        
        // cleanup users
        $q = array();
        $q[] = 'DELETE';
        $q[] = 'FROM user';
        $q[] = 'WHERE username LIKE "SG_AclTest_User%_Name";';
        $q = implode(PHP_EOL, $q);
        $db->query($q);
    }
}