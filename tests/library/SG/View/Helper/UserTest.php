<?php

class SG_View_Helper_UserTest extends PHPUnit_Framework_TestCase
{
    /**
     * setUp
     */
    public function setUp()
    {
        
    }
    
    /**
     * Teardown
     */
    public function tearDown()
    {
        
    }
  
    /**
     * Test with non logged in user
     */
    public function testAsGuest()
    {
        $helper = new SG_View_Helper_User();
        
        $this->assertInstanceOf('User_Model_Row_User', $helper->user());
        $this->assertEquals('Guest', $helper->user()->username);
    }
    
    /**
     * Test logged in
     */
    public function testAsUser()
    {
        $users = new User_Model_DbTable_User();
        $user = $users->createRow(array(
            'username' => __CLASS__,
        ));
        Zend_Auth::getInstance()->getStorage()->write($user);
        
        $helper = new SG_View_Helper_User();
        
        $this->assertInstanceOf('User_Model_Row_User', $helper->user());
        $this->assertEquals(__CLASS__, $helper->user()->username);
    }
}