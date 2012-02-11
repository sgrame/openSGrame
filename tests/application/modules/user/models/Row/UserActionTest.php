<?php

class User_Model_Row_UserActionTest extends PHPUnit_Framework_TestCase
{
    /**
     * Some fixed test data
     */
    const USER_ID          = 999999999;
    const TEST_DATE        = '2022-02-02 12:00:00';
    const TEST_DATE_FORMAT = 'YYYY-MM-dd HH:mm:ss';
    
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
        $db->query(
            'DELETE FROM user_action WHERE user_id = ?',
            array(self::USER_ID)
        );
    }
  
    /**
     * Test getting the expire date by its specific getter
     */
    public function testGetDateExpire()
    {
        $model = new User_Model_DbTable_UserAction();
        $userAction = $model->fetchNew();
        
        // empty date
        $this->assertNull($userAction->getDateExpire());
        
        // date set
        $dateString = self::TEST_DATE;
        $format     = self::TEST_DATE_FORMAT;
        
        $userAction->date_expire = self::TEST_DATE;
        $dateExpire = $userAction->getDateExpire();
        
        $this->assertInstanceOf('Zend_Date', $dateExpire);
        $this->assertEquals($dateExpire->get($format), $dateString);
    }
  
    /**
     * Testing setting the expire date by its specific setter
     */
    public function testSetDateExpire()
    {
        $model = new User_Model_DbTable_UserAction();
        $userAction = $model->fetchNew();
        
        $dateString = self::TEST_DATE;
        $format     = self::TEST_DATE_FORMAT;
        
        $date = new Zend_Date($dateString, $format);
        $userAction->setDateExpire($date);
        $this->assertEquals($date->get($format), $userAction->date_expire);
        
        $date = new Zend_Date();
        $date->add('24', Zend_Date::HOUR);
        $userAction->setDateExpire($date);
        $this->assertEquals($date->get($format), $userAction->date_expire);
    }
    
    /**
     * Testing auto token (during insert)
     */
    public function testAutoTokenOnInsert()
    {
        $model = new User_Model_DbTable_UserAction();
        $userAction = $model->fetchNew();
        $this->assertEmpty($userAction->token);
        
        $userAction->action  = __FUNCTION__;
        $userAction->user_id = self::USER_ID;
        $userAction->save();
        $this->assertNotEmpty($userAction->token);
        
        $savedAction = $model->findByUserId(self::USER_ID)->current();
        $this->assertEquals($userAction->token, $savedAction->token);
    }
}
