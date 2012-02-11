<?php

class User_Model_UserTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->_cleanUpUserActionTable();
    }
    
    public function tearDown()
    {
        $this->_cleanUpUserActionTable();
    }
    
    /**
     * Helper to clean up the user_action table
     */
    protected function _cleanUpUserActionTable()
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $stmt = $db->query(
            'DELETE FROM user_action WHERE action = ?',
            array(User_Model_User::USER_ACTION_PASSWORD_RESET)
        );
    }
    
    public function testFindById()
    {
        $model = new User_Model_User();
        $user = $model->findById(0);
        
        $this->assertInstanceOf('User_Model_Row_User', $user);
        $this->assertEquals('system', $user->username);
    }
    
    public function testFindByUsernameOrEmail()
    {
        $username = 'system';
        $email    = 'system@opensgrame.org';
      
        $model = new User_Model_User();
        $userByName = $model->findByUsernameOrEmail($username);
        $userByMail = $model->findByUsernameOrEmail($email); 
        
        $this->assertInstanceOf('User_Model_Row_User', $userByName);
        $this->assertInstanceOf('User_Model_Row_User', $userByMail);
        $this->assertEquals($userByName, $userByMail);
    }
    
    public function testResetPassword()
    {
        $username = 'admin';
        
        $model = new User_Model_User();
        $user = $model->findByUsernameOrEmail($username);
        
        $model->resetPassword($user);
        
        $actionMapper = new User_Model_DbTable_UserAction();
        $actions = $actionMapper->findByUserId(
            $user->id, User_Model_User::USER_ACTION_PASSWORD_RESET
        );
        $action = $actions->current();
        
        // check expire date
        $date = new Zend_Date();
            $date->add(24, Zend_Date::HOUR);
        $this->assertEquals(
            $date->get('YYYY-MM-dd HH'),
            $action->getDateExpire()->get('YYYY-MM-dd HH')
        );
    }
}
