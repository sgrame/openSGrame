<?php
/**
 * @group SG_Modules
 * @group SG_Modules_Activity
 */
class Activity_Model_ActivityTest 
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
        
        $this->_setUpDb();
    }
    
    /**
     * Find by id test
     */
    public function testFindById()
    {
        // test where the record does not exists
        $mapper = $this->_getDbTable();
        $mapper->expects($this->any())
               ->method('find')
               ->will($this->returnValue($this->_getRowset()));
        $model = new Activity_Model_Activity($mapper);
        $this->assertNull($model->findById(9999));
        
        // effective element
        $activity = $this->_getActivity('Activity_Model_Activity_Test');
        $rowSet   = $this->_getRowset();
        $rowSet->expects($this->any())
               ->method('current')
               ->will($this->returnValue($activity));
        $mapper = $this->_getDbTable();
        $mapper->expects($this->any())
               ->method('find')
               ->will($this->returnValue($rowSet));
        $model = new Activity_Model_Activity($mapper);
        $this->assertEquals($activity, $model->findById(1));
    }
    
    /**
     * Test find by search
     */
    public function testFindBySearch()
    {
        // test where the record does not exists
        $mapper = $this->_getDbTable();
        $mapper->expects($this->any())
               ->method('findBySearch')
               ->will($this->returnValue($this->_getRowset()));
        $model = new Activity_Model_Activity($mapper);
        $this->assertInstanceOf('Zend_Paginator', $model->findBySearch());
    }
    

    /**
     * Test exception for the extractActivityId
     * 
     * @expectedException        Zend_Db_Table_Row_Exception
     * @expectedExceptionMessage No valid activity ID or Activity object
     */
    public function testExtractActivityIdException()
    {
        Activity_Model_Activity::extractActivityId('FooBar');
    }
    
    /**
     * Test extract the record id of an object
     */
    public function testExtractActivityId()
    {
        $this->assertEquals(
            3, 
            Activity_Model_Activity::extractActivityId(3)
        );
        
        $activityRow = new Activity_Model_Row_Activity(array('data' => array(
            'id' => 5,
        )));
        $activity = $this->_getActivity(
            'Activity_Model_Activity_TestExtractId',
            $activityRow
        );
        $this->assertEquals(
            5, 
            Activity_Model_Activity::extractActivityId($activity)
        );
    }
    
    
    
    
    /**
     * Test DbTable
     * 
     * @return Activity_Model_DbTable_Activity
     */
    protected function _getDbTable()
    {
        $stub = $this->getMockBuilder('Activity_Model_DbTable_Activity')
                     ->disableOriginalConstructor()
                     ->getMock();
        return $stub;
    }
    
    /**
     * Test rowset
     * 
     * @return Activity_Model_Row_Activity
     */
    protected function _getRowset()
    {
        $stub = $this->getMockBuilder('Activity_Model_Rowset_Activity')
                     ->disableOriginalConstructor()
                     ->getMock();
        return $stub;
    }
       
    /**
     * Get an implementation of the Activity_Model_Activity_Abstract object
     * 
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function _getActivity($className = NULL, $activity = NULL)
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
     * Create the test db
     * 
     * @return void
     */
    protected function _setupDb()
    {
        $db = $this->setTestDbAsDefault();
        
        // create the users tables
        require_once './application/modules/user/models/Db/Structure.php';
        User_Model_Db_Structure::createAllTables($db);
        User_Model_Db_Structure::populateAllTables($db);

        // create the activity tables
        require_once './application/modules/activity/models/Db/Structure.php';
        Activity_Model_Db_Structure::createAllTables($db);
        
        $this->_populateDb();
    }
    
    /**
     * Prefill the database
     * 
     * @return void
     */
    protected function _populateDb()
    {
        $db = $this->getTestDb();
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
