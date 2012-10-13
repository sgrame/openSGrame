<?php
/**
 * @group SG_Modules
 * @group SG_Modules_Activity
 */
class Activity_Model_Row_ActivityTest 
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
    }
    
    public function tearDown()
    {
    }
    
    /**
     * Test the parameter functionality
     */
    public function testParams()
    {
        $activity = $this->_getActivity();
        
        // test get params
        $params = $activity->getParams();
        $this->assertEquals(
            array(
                'param1' => 'value1',
                'param2' => 'value2',
            ), 
            $params
        );
        
        // test set params
        $newParams = array(
            'param3' => 'value3',
            'param4' => 'value4',
            'param5' => array('param51' => 'value51'),
        );
        $this->assertInstanceOf(
            'Zend_Db_Table_Row_Abstract', 
            $activity->setParams($newParams)
        );
        $this->assertEquals($newParams, $activity->getParams());
    }
    
    /**
     * Test the save functionality
     */
    public function testSave()
    {
        // connect
        $db = Zend_Db::factory(
            'Pdo_Sqlite', 
            array('dbname' => ':memory:')
        );
        Zend_Db_Table::setDefaultAdapter($db);

        // create the activity tables
        require_once './application/modules/activity/models/Db/Structure.php';
        Activity_Model_Db_Structure::createAllTables($db);
        
        $activity  = $this->_getActivity();
        $this->assertNull($activity->cd);
        
        $newParams = array('new' => 'value');
        $activity->setParams($newParams);
        $activity->save();
        $this->assertEquals($newParams, unserialize($activity->params));
        $this->assertNotNull($activity->cd);
    }
    
    
    /**
     * Create a test activity record
     * 
     * @return Activity_Model_Row_Activity
     */
    protected function _getActivity()
    {
        $params = serialize(array(
            'param1' => 'value1',
            'param2' => 'value2',
        ));
        
        return new Activity_Model_Row_Activity(array(
            'data' => array(
                'model'    => 'model-name',
                'type'     => 'type-name',
                'params'   => $params,
                'owner_id' => 1,
                'created'  => '2012-10-01 12:00:00',
                'ci'       => NULL,
                'cd'       => NULL,
            )
        ));
    }
}
