<?php
/**
 * @group SG
 * @group SG_Db
 * @group SG_Db_Model
 */
class SG_Db_ModelTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test the constructor
     */
    public function testConstructor()
    {
        $model = new SG_Db_Model();
        $this->assertNull($model->getTable());
        $this->assertNull($model->getTableClass());
        
        $table = $this->_getDbTable();
        $model2 = new SG_Db_Model(array('table' => $table));
        $this->assertInstanceOf('Zend_Db_Table', $model2->getTable());
    }
  
    /**
     * Test the tableClass get/setters
     */
    public function testGetSetTableClass()
    {
        $model = new SG_Db_Model();
        $this->assertNull($model->getTableClass());
        
        $this->assertInstanceOf('SG_Db_Model', $model->setTableClass('FooBar'));
        $this->assertEquals('FooBar', $model->getTableClass());
    }
    
    /**
     * Test the table get/setters
     */
    public function testGetSetTable()
    {
        $model = new SG_Db_Model();
        $this->assertNull($model->getTable());
        
        $table = $this->_getDbTable();
        $tableClass = get_class($table);
        $this->assertInstanceOf('SG_Db_Model', $model->setTable($table));
        $this->assertEquals($table, $model->getTable());
        $this->assertEquals($tableClass, $model->getTableClass());
    }
    
    /**
     * Test the find by id method
     */
    public function testFindById()
    {
        $row    = $this->_getDbTableRow();
        $rowset = $this->_getDbTableRowset();
        $rowset ->expects($this->any())
                ->method('current')
                ->will($this->returnValue($row));
        $table  = $this->_getDbTable();
        $table  ->expects($this->any())
                ->method('find')
                ->will($this->returnValue($rowset));
        
        $model = new SG_Db_Model(array('table' => $table));
        $this->assertEquals($row, $model->findById(1));
        
        $rowset2 = $this->_getDbTableRowset();
        $rowset2 ->expects($this->any())
                 ->method('current')
                 ->will($this->returnValue(NULL));
        $table2  = $this->_getDbTable();
        $table2  ->expects($this->any())
                 ->method('find')
                 ->will($this->returnValue($rowset2));
        
        $model2 = new SG_Db_Model(array('table' => $table2));
        $this->assertNull($model2->findById(9999));
    }
    
    /**
     * Test create search query
     */
    public function testSearch()
    {
        $row    = $this->_getDbTableRow();
        $rowset = $this->_getDbTableRowset();
        $rowset ->expects($this->any())
                ->method('current')
                ->will($this->returnValue($row));
        $table  = $this->_getDbTable();
        $table  ->expects($this->any())
                ->method('fetchAll')
                ->will($this->returnValue($rowset));
        $table  ->expects($this->any())
                ->method('search')
                ->will($this->returnValue($rowset));
        
        $model = new SG_Db_Model(array('table' => $table));
        $this->assertInstanceOf(
            'Zend_Paginator', 
            $model->search()
        );
        $options = array('itemCountPerPage' => 'ALL');
        $this->assertInstanceOf(
            'Zend_Db_Table_Rowset', 
            $model->search(array(), 0, array(), $options)
        );
    }
    
    /**
     * Test to test the isRow functionality
     */
    public function testIsRow()
    {
        $table = $this->_getDbTable();
        $table  ->expects($this->any())
                ->method('getRowClass')
                ->will($this->returnValue('Zend_Db_Table_Row'));
        $row   = $this->_getDbTableRow();
        
        $model = new SG_Db_Model(array('table' => $table));
        $this->assertTrue($model->isRow($row));
        
        $test1 = new stdClass();
        $this->assertFalse($model->isRow($test1));
        $test2 = NULL;
        $this->assertFalse($model->isRow($test2));
        $test3 = 123;
        $this->assertFalse($model->isRow($test3));
        $test4 = 'FOO';
        $this->assertFalse($model->isRow($test4));
    }
    
    /**
     * Test the extractId functionality
     */
    public function testExtractId()
    {
        // simple by giving an integer
        $this->assertEquals(5, SG_Db_Model::extractId(5));
        
        // by giving a row
        $row = $this->_getDbTableRow();
        $row->id = 555;
        $this->assertEquals(
            555, 
            SG_Db_Model::extractId(555, new SG_Db_Model()
        ));
    }
    
    /**
     * Test the static load functionality
     */
    public function testLoad()
    {
        $table = $this->_getDbTable();
        $table  ->expects($this->any())
                ->method('getRowClass')
                ->will($this->returnValue('Zend_Db_Table_Row'));
        $table  ->expects($this->any())
                ->method('info')
                ->will($this->returnValue(array('primary' => array('id'))));
        $row = new Zend_Db_Table_Row(array(
            'table' => $table,
            'data'  => array(
                'id' => 6,
            ),
            'stored' => true,
        ));
        $model = new SG_Db_Model(array('table' => $table));
        
        // simple by passing an object
        $this->assertEquals($row, SG_Db_Model::load($row, false, $model));
        // now we can load the row by its id from the cache
        $this->assertEquals($row, SG_Db_Model::load(6, false, $model));
        
        
        // trough the findById() method by passing record id
        $row2 = new Zend_Db_Table_Row(array(
            'table' => $table,
            'data'  => array(
                'id' => 101,
            ),
            'stored' => true,
        ));
        $rowset2 = $this->_getDbTableRowset();
        $rowset2 ->expects($this->once())
                 ->method('current')
                 ->will($this->returnValue($row2));
        $table2  = $this->_getDbTable();
        $table2  ->expects($this->once())
                 ->method('find')
                 ->will($this->returnValue($rowset2));
        
        $model2 = new SG_Db_Model(array('table' => $table2));
        
        // simple by passing an object
        $this->assertEquals($row2, SG_Db_Model::load(101, false, $model2));
        // now we can load the row by its id from the cache
        $this->assertEquals($row2, SG_Db_Model::load(101, false, $model2));
    }

        
    
    /**
     * Test DbTable
     * 
     * @return Zend_Db_table
     */
    protected function _getDbTable()
    {
        $stub = $this->getMockBuilder('Zend_Db_Table')
                     ->disableOriginalConstructor()
                     ->getMock();
        return $stub;
    }
    
    /**
     * Test DbTable Rowset
     * 
     * @return Zend_Db_Table_Row
     */
    protected function _getDbTableRowset()
    {
        $stub = $this->getMockBuilder('Zend_Db_Table_Rowset')
                     ->disableOriginalConstructor()
                     ->getMock();
        return $stub;
    }
    
    /**
     * Test DbTable Row
     * 
     * @return Zend_Db_Table_Row
     */
    protected function _getDbTableRow()
    {
        $stub = $this->getMockBuilder('Zend_Db_Table_Row')
                     ->disableOriginalConstructor()
                     ->getMock();
        return $stub;
    }
    
    
}

