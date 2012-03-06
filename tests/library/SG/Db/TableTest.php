<?php
/**
 * @group SG
 */
class SG_Db_TableTest extends SG_Test_PHPUnit_ControllerTestCase
{
    /**
     * Test table name
     * 
     * @var string 
     */
    protected $_tableName       = 'phpunit_sg_db_table_test';
    protected $_tableNameNoMeta = 'phpunit_sg_db_table_test_no_meta';
  
    /**
     * Loads the bootstrap
     */
    public function setUp()
    {
        $this->bootstrap = new Zend_Application(
            APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini'
        );
        parent::setUp();
        
        $this->_setUpTestTable();
    }
  
    /**
     * tearDown
     */
    public function tearDown()
    {
        $this->_tearDownTestTable();
    }
  
  
    /**
     * Test the contingency getter
     */
    public function testGetContingency() 
    {
        $table = $this->_getTableObject();
        $this->assertFalse($table->getContingency());
        $table->setContingency(TRUE);
        $this->assertTrue($table->getContingency());
    }
    
    /**
     * Test the contingency setter
     */
    public function testSetContingency()
    {
        // test with table without CR field
        $table = $this->_getTableWithoutMetaObject();
        try {
            $table->setContingency(TRUE);
            $this->fail('Setting the Contignency on tables without should trigger an exception');
        }
        catch(Exception $e) {
            $this->assertInstanceOf('Zend_Db_Table_Exception', $e);
        }
        
        $table = $this->_getTableObject();
        $this->assertInstanceOf('SG_Db_Table', $table->setContingency(TRUE));
    }
    
    /**
     * Test the has contingency field method
     */
    public function testHasContingencyField()
    {
        $table = $this->_getTableWithoutMetaObject();
        $this->assertFalse($table->hasContingencyField());
        
        $table = $this->_getTableObject();
        $this->assertTrue($table->hasContingencyField());
    }
    
    /**
     * Test the has created field method
     */
    public function testHasCreatedField()
    {
        $table = $this->_getTableWithoutMetaObject();
        $this->assertFalse($table->hasCreatedField());
        
        $table = $this->_getTableObject();
        $this->assertTrue($table->hasCreatedField());
    }
    
    /**
     * Test the has owner id field method
     */
    public function testHasOwnerIdField()
    {
        $table = $this->_getTableWithoutMetaObject();
        $this->assertFalse($table->hasOwnerIdField());
        
        $table = $this->_getTableObject();
        $this->assertTrue($table->hasOwnerIdField());
    }
    
    /**
     * Test the extended insert functionality
     */
    public function testInsert()
    {
        $table = $this->_getTableObject(); 
        
        // not logged in
        $data = array('data' => 'test1');
        $id = $table->insert($data);
        $row = $table->find($id)->current();
        
        $this->assertNotEmpty($row->cd);
        $this->assertEquals('0', $row->ci);
        $this->assertNull($row->cr);
        
        // logged in
        $this->loginAdmin();
        $data = array('data' => 'test2');
        $id = $table->insert($data);
        $row = $table->find($id)->current();
        $this->assertEquals('1', $row->ci);
    }
    
    /**
     * Test the auto created date and owner_id properties
     */
    public function testInsertCreatedAndOwnerId()
    {
        $table = $this->_getTableObject();
        
        // not logged in
        $data = array('data' => 'test1');
        $id = $table->insert($data);
        $row = $table->find($id)->current();
        
        $this->assertEquals('0', $row->ci);
        $this->assertNotNull($row->created);
        $this->assertNotEquals('0000-00-00 00:00:00', $row->created);
    }
    
    /**
     * Test the extended update functionality
     */
    public function testUpdate()
    {
        $table = $this->_getTableObject();
        $id    = $table->insert(array('data' => 'testUpdate1'));
        $this->assertEquals(1, $this->_countTableRecords($this->_tableName));
        
        // test update the user id
        $before = $table->find($id)->current();
        $this->assertEquals('0', $before->ci);
        $this->loginAdmin();
        $table->update(array('data' => 'testUpdate10'), 'id = ' . $id);
        $after = $table->find($id)->current();
        $this->assertEquals('1', $after->ci);
        
        // update without CR disabled
        $table->update(array('data' => 'testUpdate2'), 'id = ' . $id);
        $this->assertEquals(1, $this->_countTableRecords($this->_tableName));
        
        // update with CR enabled
        $table->setContingency(true);
        $table->update(array('data' => 'testUpdate3'), 'id = ' . $id);
        $this->assertEquals(2, $this->_countTableRecords($this->_tableName));
        
        // cleanup
        $this->_setUpTestTable();
        
        // test without changing data > should not do any update!
        $table->setContingency(true);
        $data = array('data' => 'testUpdate4');
        $id = $table->insert($data);
        $this->assertEquals(1, $this->_countTableRecords($this->_tableName));
        
        $table->update($data, 'id = ' . $id);
        $this->assertEquals(1, $this->_countTableRecords($this->_tableName));
    }

    /**
     * Test the extended delete functionality
     */
    public function testDelete()
    {
        $table = $this->_getTableObject();
        $id    = $table->insert(array('data' => 'testUpdate1'));
        $this->assertEquals(1, $this->_countTableRecords($this->_tableName));
        
        // without CR
        $table->delete('id = ' . $id);
        $this->assertEquals(0, $this->_countTableRecords($this->_tableName));
        
        // with CR
        $table->setContingency(true);
        $id    = $table->insert(array('data' => 'testUpdate2'));
        $this->assertEquals(1, $this->_countTableRecords($this->_tableName));
        
        $table->delete('id = ' . $id);
        $this->assertEquals(2, $this->_countTableRecords($this->_tableName));
        
        $this->assertNull($table->find($id)->current());
    }
    
    /**
     * Test select with auto where "cr IS NULL"
     */
    
    
    /**
     * 
     */
    
    
    
    
    
    /**
     * Count the number of records in the given table
     * 
     * @param string $table
     * 
     * @return int
     */
    protected function _countTableRecords($table)
    {
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $select = $db->select();
        $select->from($table, array(
            'count' => new Zend_Db_Expr('COUNT(1)'),
        ));
        return (int)$select->query()->fetchColumn(0);
    }
    
    /**
     * Get a table object
     * 
     * @return SG_Db_Table
     */
    public function _getTableObject()
    {
        return new SG_Db_Table($this->_tableName);
    }
    
    /**
     * Get a table object without meta data
     * 
     * @return SG_Db_Table
     */
    public function _getTableWithoutMetaObject()
    {
        return new SG_Db_Table($this->_tableNameNoMeta);
    }
    
    
    /**
     * Set up the test database
     */
    protected function _setUpTestTable()
    {
        $this->_tearDownTestTable();
        $db = Zend_Db_Table::getDefaultAdapter();

        $q = array();
        $q[] = 'CREATE TABLE IF NOT EXISTS `' . $this->_tableNameNoMeta . '` (';
        $q[] = '  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT "record id",';
        $q[] = '  `data` varchar(256) DEFAULT NULL COMMENT "test data",';
        $q[] = '  PRIMARY KEY (`id`)';
        $q[] = ') ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT="Test table without meta data for unit testing" AUTO_INCREMENT=1 ;';
        $q = implode(PHP_EOL, $q);
        $db->query($q);

        $q = array();
        $q[] = 'CREATE TABLE IF NOT EXISTS `' . $this->_tableName . '` (';
        $q[] = '  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT "record id",';
        $q[] = '  `data` varchar(256) DEFAULT NULL COMMENT "test data",';
        $q[] = '  `owner_id` int(11) unsigned NOT NULL COMMENT "Record owner ID",';
        $q[] = '  `created` datetime NOT NULL COMMENT "Create date of this record (first date)",';
        $q[] = '  `ci` int(11) unsigned NOT NULL COMMENT "The user who has wroten this record",';
        $q[] = '  `cd` datetime NOT NULL COMMENT "The date/time this record was inserted or updated",';
        $q[] = '  `cr` int(11) unsigned DEFAULT NULL COMMENT "If not NULL, this record is a history copy of the original record ID filled in here",';
        $q[] = '  PRIMARY KEY (`id`),';
        $q[] = '  KEY `ci` (`ci`),';
        $q[] = '  KEY `cd` (`cd`),';
        $q[] = '  KEY `owner_id` (`owner_id`)';
        $q[] = ') ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT="Test table for unit testing" AUTO_INCREMENT=1 ;';
        $q = implode(PHP_EOL, $q);
        $db->query($q);
    }
    
    /**
     * Cleanup the test data
     */
    protected function _tearDownTestTable()
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        
        $q = 'DROP TABLE IF EXISTS `' . $this->_tableNameNoMeta . '`;';
        $db->query($q);
        
        $q = 'DROP TABLE IF EXISTS `' . $this->_tableName . '`;';
        $db->query($q);
    }
}

