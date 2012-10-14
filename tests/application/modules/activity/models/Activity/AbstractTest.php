<?php
/**
 * @group SG_Modules
 * @group SG_Modules_Activity
 */
class Activity_Model_DbTable_AbstractTest 
  extends SG_Test_PHPUnit_ControllerTestCase
{
    /**
     * Setup the framework, create the sql database 
     */
    public function setUp()
    {
        $this->bootstrap = new Zend_Application(
            APPLICATION_ENV, 
            APPLICATION_PATH . '/configs/application.ini'
        );
        parent::setUp();
        
        Zend_Db_Table::setDefaultAdapter($this->_getDb());
        $this->_populateDb();
    }
    
    public function tearDown()
    {
        $this->_db->closeConnection();
        $this->_db = NULL;
    }
    
    
    /**
     * Test constructor for a new activity
     */
    public function testConstructor()
    {
        // new Activity object
        $activity = $this->_getStub('Activity_Model_Activity_TestNew');
        /* @var $activity Activity_Model_Activity_Abstract */
        $this->assertInstanceOf('Activity_Model_Activity_TestNew', $activity);
        $row = $activity->getRow();
            $this->assertInstanceOf('Activity_Model_Row_Activity', $row);
            $this->assertEquals('Activity', $row->module);
            $this->assertEquals('TestNew', $row->type);
            $this->assertNull($row->id);
            
        // existing Activity object
        $row = $this->_getActivityRow();
        $activity = $this->_getStub('Activity_Model_Activity_TestExisting', $row);
            $this->assertTrue($row === $activity->getRow());
            $this->assertEquals('activity-test', $activity->getRow()->module);
            $this->assertEquals('activity-type', $activity->getRow()->type);
            
        // existing Activity record ID
        $id = 1;
        $activity = $this->_getStub('Activity_Model_Activity_TestExistingId', $id);
            $this->assertEquals($id, $activity->getRow()->id);
            $this->assertEquals('module1', $activity->getRow()->module);
            $this->assertEquals('type1', $activity->getRow()->type);
    }
    
    /**
     * Test get & set params
     */
    public function testParams()
    {
        $activity = $this->_getStub(
            'Activity_Model_Activity_TestParams', 
            $this->_getActivityRow()
        );
        $this->assertEquals(
            $activity->getRow()->getParams(), 
            $activity->getParams()
        );
        
        $newParams = array('new-1' => 'val-1', 'new-2' => 'val-2');
        $this->assertInstanceOf(
            'Activity_Model_Activity_Abstract', 
            $activity->setParams($newParams)
        );
        $this->assertEquals($newParams, $activity->getParams());
        $this->assertEquals($newParams, $activity->getRow()->getParams());
    }
    
    /**
     * Test retrieving specific param
     */
    public function testGetParam()
    {
        $activity = $this->_getStub(
            'Activity_Model_Activity_TestGetParams', 
            $this->_getActivityRow()
        );
        
        // non existing
        $this->assertNull($activity->getParam('NON-EXISTING-PARAM-KEY'));
        $this->assertEquals(
            'TEST123', 
            $activity->getParam('NON-EXISTING-PARAM-KEY', 'TEST123')
        );
        
        // existing
        $this->assertEquals('value3', $activity->getParam('param3'));
    }
    
    /**
     * Test set specific param
     */
    public function testSetParam()
    {
        $activity = $this->_getStub(
            'Activity_Model_Activity_TestSetParams', 
            $this->_getActivityRow()
        );
        
        $this->assertInstanceOf(
            'Activity_Model_Activity_Abstract', 
            $activity->setParam('NewParam', 'newValue')
        );
        $params = $activity->getRow()->getParams();
        $this->assertEquals(
            $params['NewParam'], 
            $activity->getParam('NewParam')
        );
    }
    
    /**
     * Test get the default title
     */
    public function testGetTitle()
    {
        $activity = $this->_getStub(
            'Activity_Model_Activity_TestTitle', 
            $this->_getActivityRow()
        );
        $this->assertNull($activity->getTitle());
    }
    
    /**
     * Test get the default description
     */
    public function testGetDescription()
    {
        $activity = $this->_getStub(
            'Activity_Model_Activity_TestDescription', 
            $this->_getActivityRow()
        );
        $this->assertEquals(array(), $activity->getDescription());
    }
    
    /**
     * Test get the default actions
     */
    public function testGetActions()
    {
        $activity = $this->_getStub(
            'Activity_Model_Activity_TestActions', 
            $this->_getActivityRow()
        );
        $this->assertEquals(array(), $activity->getActions());
    }
    
    /**
     * Test get the user object
     */
    public function testGetUser()
    {
        $activity = $this->_getStub(
            'Activity_Model_Activity_TestUser', 
            $this->_getActivityRow()
        );
        $this->assertInstanceOf('User_Model_Row_User', $activity->getUser());
        $this->assertEquals(1, $activity->getUser()->id);
    }
    
    /**
     * Test the get data functionality
     */
    public function testGetDate()
    {
        $activity = $this->_getStub(
            'Activity_Model_Activity_TestDate', 
            $this->_getActivityRow()
        );
        $this->assertInstanceOf('Zend_Date', $activity->getDate());
        $this->assertEquals(
            '2012-10-01 12:00:00', 
            $activity->getDate()->get('yyyy-MM-dd HH:mm:ss')
        );
    }
    
    /**
     * Test the save functionality
     */
    public function testSave()
    {
        // test save with given record
        $activity = $this->_getStub(
            'Activity_Model_Activity_TestSave', 
            $this->_getActivityRow()
        );
        
        $this->assertInstanceOf(
            'Activity_Model_Activity_Abstract', 
            $activity->save()
        );
        
        $this->assertEquals(2, $activity->getRow()->id);
        
        $this->assertEquals('activity-test', $activity->getRow()->module);
        $this->assertEquals('activity-type', $activity->getRow()->type);
        
        
        // test save with a new record
        $activity = $this->_getStub('Activity_Model_Activity_TestSaveNew');
        $activity->save();
        $this->assertEquals(3, $activity->getRow()->id);
        $this->assertEquals('Activity', $activity->getRow()->module);
        $this->assertEquals('TestSaveNew', $activity->getRow()->type);
    }
    
    
    
    
    /**
     * Get a stub for the abstract class
     * 
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function _getStub($className = NULL, $activity = NULL)
    {
        $args = (!empty($activity))
            ? array($activity)
            : array();
        
        return $this->getMockForAbstractClass(
            'Activity_Model_Activity_Abstract',
            $args,
            $className
        );
    }
    
    /**
     * Get a Activity_Model_Row_Activity
     * 
     * @return Activity_Model_Row_Activity
     */
    protected function _getActivityRow()
    {
        $activities = new Activity_Model_DbTable_Activity(array(
            Zend_Db_Table::ROWSET_CLASS => 'Zend_Db_Table_Rowset' 
        ));
        
        $params = array(
            'param1' => 'value1',
            'param2' => 'value2',
            'param3' => 'value3',
        );
        return $activities->createRow(array(
            'module'   => 'activity-test',
            'type'     => 'activity-type',
            'params'   => serialize($params),
            'owner_id' => 1,
            'created'  => '2012-10-01 12:00:00',
            'ci'       => 1,
            'cd'       => '2012-10-01 12:00:00',
        ));    
    }
    
    
    /**
     * Create the test db
     * 
     * @return void
     */
    protected function _getDb()
    {
        if (!$this->_db) {
            // connect
            $this->_db = Zend_Db::factory(
                'Pdo_Sqlite', 
                array('dbname' => ':memory:')
            );

            // create the users tables
            require_once './application/modules/user/models/Db/Structure.php';
            User_Model_Db_Structure::createAllTables($this->_db);
            User_Model_Db_Structure::populateAllTables($this->_db);
            
            // create the activity tables
            require_once './application/modules/activity/models/Db/Structure.php';
            Activity_Model_Db_Structure::createAllTables($this->_db);
        }
        
        return $this->_db;
    }
    
    /**
     * Prefill the database
     * 
     * @return void
     */
    protected function _populateDb()
    {
        $db = $this->_getDb();
        $param1 = $db->quote(serialize(array(
            'param1-1' => 'value1-1',
            'param1-2' => 'value1-2',
            'param1-3' => 'value1-3',
            'param1-4' => 'value1-4',
        )));
        $fields = array(
            'id', 'module', 'type', 'params', 'owner_id', 'created', 'ci', 'cd',
        );
        $data = array(
            array(1, 'module1', 'type1', $param1, 2, '2012-10-01 12:00:00', 2, '2012-10-01 12:00:00'),
        );
        foreach($data AS $row) {
            $db->insert('activity', array_combine($fields, $row));
        }
    }
}
