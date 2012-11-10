<?php
/**
 * @category SG
 * @package  Db
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Db_Model
 *
 * Base class for models based on database tables
 *
 * @category SG
 * @package  Db
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
abstract class SG_Db_Model_Abstract implements SG_Db_Model_Interface
{
    /**
     * The table class name
     * 
     * @var string
     */
    protected $_tableClass = NULL;
    
    /**
     * The table instance
     * 
     * @var Zend_Db_Table
     */
    protected $_table;
    
    
    /**
     * Class constructor
     * 
     * @param array $options
     *     (optional) array of options
     */
    public function __construct($options = array())
    {
        if (isset($options['table'])) {
            $this->setTable($options['table']);
        }
        elseif (isset($options['tableClass'])) {
            $this->setTableClass($options['tableClass']);
        }
        
        if (!$this->getTable() && $this->getTableClass()) {
            $tableClass = $this->getTableClass();
            $this->setTable(new $tableClass());
        }
    }
    
    
    
    /**
     * Find a single row by its record id
     * 
     * @param int
     * 
     * @return Zend_Db_Table_Row
     */
    public function findById($id)
    {
        return $this->_table->find($id)->current();
    }
    
    /**
     * Find a collection of records by an array of search params.
     * 
     * The list is returned, by default, as a paginated list.
     * You can request to have the Rowset instead by setting the 
     * $options['itemCountPerPage'] to "ALL".
     * 
     * WARNING: If the table class does not have a search() method, the fetchAll()
     * method will be used as fallback.
     * 
     * @param array $search
     *     (optional) the search parameters
     * @param int $page
     *     (optional) the page number
     * @param array $order
     *     (optional) the field => order how the result set should be ordered
     * @param array $options
     *     (optional) optional settings for the paginator
     *     Supported options:
     *     -  itemCountPerPage: 
     *          How many ites should there be in each page
     *          If set to "ALL", the Zend_Db_Table_Rowset object will be the 
     *          return value.
     * 
     * @return Zend_Paginator|Zend_Db_Table_Rowset
     */
    public function search(
        $search = array(), $page = 0, $order = array(), $options = array()
    )
    {
        $itemCountPerPage = (!empty($options['itemCountPerPage']))
            ? $options['itemCountPerPage']
            : NULL;
        
        // Check for special search() method within the table class
        if (method_exists($this->_table, 'search')) {
            $result = $this->_table->search($search, $order);
        }
        // Fallback to the fetchAll() method
        else {
            $result = $this->_table->fetchAll();
        }
        
        if ('ALL' === $itemCountPerPage) {
            return $result;
        }
        
        $paged = Zend_Paginator::factory($result);
        $paged->setCurrentPageNumber((int)$page);
        if (is_numeric($itemCountPerPage)) {
            $paged->setItemCountPerPage($itemCountPerPage);
        }
        return $paged;
    }
    
    /**
     * Helper to determen if the given object is an instance of the expected 
     * row class
     * 
     * @param Zend_Db_Table_Row $row
     * 
     * @return bool
     */
    public function isRow($row)
    {
        if (!is_object($row)) {
            return FALSE;
        }
        
        $rowClass = $this->_table->getRowClass();
        return ($row instanceof $rowClass);
    }
    
    /**
     * "Magic" function to return the record id out of the given id or the 
     * row object
     * 
     * @param int|Zend_Db_Table_Row $row
     * @param SG_Db_Model $model
     *     (optional)
     * 
     * @return int
     */
    public static function extractId($row, $model = NULL)
    {
        if (is_numeric($row)) {
            return (int)$row;
        }
        
        if (!$model) {
            $model = new self();
        }
        if ($model->isRow($row)) {
            return $row->id;
        }
        
        throw new Zend_Db_Table_Row_Exception(
            'No valid row ID or row object'
        );
    }
    
    /**
     * "Magic" function to load a single record by its ID or return the given
     * row.
     * 
     * The row objects are staticaly cached.
     * 
     * @param int|Zend_Db_Table_Row $row
     * @param bool $reset
     *     (optional) should the static cache be cleared
     * @param SG_Db_Model $model
     *     (optional)
     * 
     * @return Zend_Db_Table_Row
     */
    public static function load($row, $reset = false, $model = NULL)
    {
        static $rows = array();
        
        if ($reset) {
            $rows = array();
        }
        
        if (!$model) {
            $model = new self();
        }
        
        // get the rowId
        $rowId = NULL;
        if (is_numeric($row)) {
            $rowId = (int)$row;
        } 
        elseif ($model->isRow($row)) {
            $rowId = (int)$row->id;
            $rows[$rowId] = $row;
        }
        else {
            return NULL;
        }
        
        // load object if not already in object store
        if (!isset($rows[$rowId])) {
            $rows[$rowId] = $model->findById($rowId);
        }
        
        return $rows[$rowId];
    }
    
    
    /**
     * Set the table object
     * 
     * @param Zend_Db_Table $table
     * 
     * @return SG_Db_Model_Interface
     */
    public function setTable(Zend_Db_Table $table) {
        $this->_table      = $table;
        $this->_tableClass = get_class($table);
        return $this;
    }
    
    /**
     * Get the table object
     * 
     * @param void
     * 
     * @return Zend_Db_Table
     */
    public function getTable() {
        return $this->_table;
    }
    
    /**
     * Set the table class name
     * 
     * @param string $tableClass
     * 
     * @return SG_Db_Model_Interface
     */
    public function setTableClass($tableClass) {
        $this->_tableClass = $tableClass;
        
        return $this;
    }
    
    /**
     * Get the table class name
     * 
     * @param void
     * 
     * @return string
     */
    public function getTableClass() {
        return $this->_tableClass;
    }
}