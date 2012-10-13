<?php
/**
 * @group SG_Modules
 * @group SG_Modules_Activity
 */
class Activity_Model_DbTable_ActivityTest 
  extends SG_Test_PHPUnit_ControllerTestCase
{
    /**
     * SQLlite memory database to test the mapper
     * 
     * @var Zend_Db_Adapter_Pdo_Sqlite
     */
    protected $_db;
    
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
     * Test db exists
     */
    public function testSearch()
    {
        $table = $this->_getDbTable();
        $this->assertInstanceOf('Activity_Model_DbTable_Activity', $table);
        
        // default search should return all ordered by created date
        $result = $table->findBysearch();
        $this->assertInstanceOf('Zend_Db_Table_Rowset', $result);
        $this->assertEquals(4, $result->count());
        $currentId = 4;
        foreach($result AS $row) {
            $this->assertEquals($currentId, $row->id);
            $currentId--;
        }
        
        // custom order
        $result = $table->findBySearch(array(), array('id' => 'ASC'));
        $currentId = 1;
        foreach($result AS $row) {
            $this->assertEquals($currentId, $row->id);
            $currentId++;
        }
        
        // get the users
        $users = new User_Model_User();
        $user2 = $users->findById(2);
        $user3 = $users->findById(3);
        
        // search by single owner
        $result = $table->findBySearch(array('owners' => $user2->id));
        foreach($result AS $row) {
            $this->assertEquals($user2->id, $row->owner_id);
        }
        
        // search by multiple owners
        $result = $table->findBySearch(array('owners' => array(
            $user2->id, $user3
        )));
        $this->assertEquals(3, $result->count());
        
        // search by rowset
        $userMapper = new User_Model_DbTable_User();
        $foundUsers = $userMapper->fetchBySearch(array('groups' => 2));
        $result     = $table->findBySearch(array('owners' => $foundUsers));
        $this->assertEquals(2, $result->count());
            
        // search by the owners alias "users"
        $result = $table->findBySearch(array('users' => $user3->id));
        $this->assertEquals(1, $result->count());
        
        
        // get the groups
        $groups = new User_Model_Group();
        $group2 = $groups->findById(2);
        $group3 = $groups->findById(3);
        
        // search by single group
        $result = $table->findBySearch(array('groups' => $group2->id));
        $this->assertEquals(2, $result->count());
        
        // search by multiple groups
        $result = $table->findBySearch(array('groups' => array(
            $group2, $group3->id
        )));
        $this->assertEquals(3, $result->count());
        
        // search by groups rowset
        $groupMapper = new User_Model_DbTable_Group();
        $foundGroups = $groupMapper->findBySearch(array('name' => 'Group 2'));
        $result      = $table->findBySearch(array('groups' => $foundGroups));
        $this->assertEquals(1, $result->count());
        
        
        // search by single module
        $result = $table->findBySearch(array('modules' => 'module1'));
        $this->assertEquals(2, $result->count());
        
        // search by multiple modules
        $result = $table->findBySearch(array('modules' => array('module1', 'module2')));
        $this->assertEquals(3, $result->count());
        
        
        // search by single type
        $result = $table->findBySearch(array('types' => 'type1'));
        $this->assertEquals(2, $result->count());
        
        // search by multiple types
        $result = $table->findBySearch(array('types' => array('type1', 'type3')));
        $this->assertEquals(3, $result->count());
        
        
        // search by single module:type
        $result = $table->findBySearch(array('module:types' => 'module1:type1'));
        $this->assertEquals(1, $result->count());
        
        // search by multiple module:types
        $result = $table->findBySearch(array('module:types' => array(
            'module1:type1', 'module2:type1'
        )));
        $this->assertEquals(2, $result->count());
    }
    
    
    /**
     * Get the Table mapper
     * 
     * This will set row and rowset classes to the default because we want to 
     * test the mapper stand-alone.
     * 
     * @return Activity_Model_DbTable_Activity
     */
    protected function _getDbTable()
    {
        return new Activity_Model_DbTable_Activity(array(
            Zend_Db_Table::ROWSET_CLASS => 'Zend_Db_Table_Rowset',
            Zend_DB_Table::ROW_CLASS    => 'Zend_Db_Table_Row',
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
        $param2 = $db->quote(serialize(array(
            'param2-1' => 'value2-1',
            'param2-2' => 'value2-2',
            'param2-3' => 'value2-3',
            'param2-4' => 'value2-4',
        )));
        $param3 = $db->quote(serialize(array(
            'param3-1' => 'value3-1',
            'param3-2' => 'value3-2',
            'param3-3' => 'value3-3',
            'param3-4' => 'value3-4',
        )));
        $param4 = $db->quote(serialize(array(
            'param4-1' => 'value4-1',
            'param4-2' => 'value4-2',
            'param4-3' => 'value4-3',
            'param4-4' => 'value4-4',
        )));
        $fields = array(
            'module', 'type', 'params', 'owner_id', 'created', 'ci', 'cd',
        );
        $data = array(
            array('module1', 'type1', $param1, 2, '2012-10-01 12:00:00', 2, '2012-10-01 12:00:00'),
            array('module1', 'type2', $param2, 3, '2012-10-02 12:00:00', 3, '2012-10-02 12:00:00'),
            array('module2', 'type1', $param3, 1, '2012-10-03 12:00:00', 1, '2012-10-03 12:00:00'),
            array('module3', 'type3', $param4, 2, '2012-10-04 12:00:00', 2, '2012-10-04 12:00:00'),
        );
        foreach($data AS $row) {
            $db->insert('activity', array_combine($fields, $row));
        }
    }
}
