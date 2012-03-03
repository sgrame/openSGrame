<?php
/**
 * @category SG_Test
 * @package  PHPUnit
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Test_PHPUnit_ControllerTestCase
 *
 * Extended controller test case that adds login and ACL support to the tests 
 *
 * @category SG_Test
 * @package  PHPUnit
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Test_PHPUnit_ControllerTestCase extends Zend_Test_PHPUnit_ControllerTestCase
{
    /**
     * Logout a possible user before starting a test
     */
    public function setUp()
    {
        parent::setUp();
        $this->logoutUser();
    }
  
    /**
     * Logout a possible logged in user
     */
    public function tearDown()
    {
        $this->logoutUser();
    }

    /**
     * Give the current "user" some resources and permissions
     * 
     * @param array
     *     Array of resources
     *       'resourcename1' => array('privilege1', 'privilege2'),
     *       'resourcename2' => array('privilege1', 'privilege2'),
     * @param string|User_Model_Row_User
     *     Username (dummy user) or User object
     * 
     * @return SG_Acl
     */
    public function setUpAcl(
        $resources, $username = 'OPENSGRAME_UNITTEST_USER'
    ) 
    {
        $user = ($username instanceof User_Model_Row_User)
            ? $username
            : null;
      
        // always start with a clean version
        $acl = new SG_Acl($user);
        Zend_Registry::set('acl', $acl);
        
        // check if we have already a logged in user (if there is a loginUser())
        // call before setUpAcl
        $userRoleName = $acl->getCurrentUserRole();
        if($userRoleName === SG_Acl::ROLE_EVERYONE) {
            $user = $this->createUser($username);
            $acl->setCurrentUser($user);
            $userRoleName = $acl->getCurrentUserRole();
        }
        
        // add the permissions
        foreach($resources AS $resource => $permissions) {
            $acl->allow($userRoleName, $resource, $permissions);
        }
        
        return $acl;
    }
    
    /**
     * Create a (dummy) user object
     * 
     * This will not create a user in the database
     * 
     * @param string $username
     * 
     * @return User_Model_Row_User
     */
    public function createUser($username)
    {
        $users = new User_Model_DbTable_User();
        $user = $users->createRow(array(
            'username' => $username,
        ));
        return $user;
    }

    /**
     * Helper to login as an administrator (has access to all controllers)
     * 
     * @param void
     * 
     * @return User_Model_Row_User
     *     The admin user object
     */
    public function loginAdmin()
    {
        $admin = $this->createUser('OPENSGRAME_UNITTEST_USER_ADMIN');
        $admin->id = 1;
        $this->loginUser($admin);
        return $admin;
    }
    
    /**
     * Helper to login as an specific user
     * 
     * @param User_Model_Row_User
     * 
     * @return User_Model_Row_User
     */
    public function loginUser(User_Model_Row_User $user)
    {
        $auth = new User_Model_Auth();
        $auth->authenticateUser($user);
    }
    
    /**
     * Helper to logout the currently logged in user
     * 
     * @param void
     * 
     * @return void
     */
    public function logoutUser()
    {
        if(Zend_Registry::isRegistered('acl')) {
            $acl = Zend_Registry::get('acl');
            $acl->unsetCurrentUser();
        }
        
        $auth = new User_Model_Auth();
        $auth->unsetAuth();
    }
}