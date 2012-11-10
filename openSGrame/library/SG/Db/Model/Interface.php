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
 * SG_Db_Model_Interface
 *
 * This describes how Database models should contain
 *
 * @category SG
 * @package  Db
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
interface SG_Db_Model_Interface
{
    /**
     * Class constructor
     * 
     * @param array $options
     *     (optional) array of options
     *     The options are:
     *     - table : An instance of Zend_Db_Table will be used as mapper to 
     *         access the table data
     *     - tableClass : The name to use to create the table object 
     *         automatically (if no table class is within the options array)
     */
    public function __construct($options);
    
    /**
     * Find a single row by its record id
     * 
     * @param int
     * 
     * @return Zend_Db_Table_Row
     */
    public function findById($id);
    
    /**
     * Find a collection of records by an array of search params
     * The list is returned as a paginated list
     * 
     * @param array $search
     *     (optional) the search parameters
     * @param int $page
     *     (optional) the page number
     * @param array $order
     *     (optional) the field => order how the result set should be ordered
     * 
     * @return Zend_Paginator
     */
    public function search($search, $page, $order);
    
    /**
     * Helper to determen if the given object is an instance of the expected 
     * row class
     * 
     * @param Zend_Db_Table_Row $row
     * 
     * @return bool
     */
    public function isRow($row);
    
    /**
     * "Magic" function to return the record id out of the given id or the 
     * row object
     * 
     * @param int|Zend_Db_Table_Row $row
     * 
     * @return int
     */
    public static function extractId($row);
    
    /**
     * "Magic" function to load a single record by its ID or return the given
     * row.
     * 
     * The row objects are staticaly cached.
     * 
     * @param int|Zend_Db_Table_Row $row
     * @param bool $reset
     *     (optional) should the static cache be cleared
     * 
     * @return Zend_Db_Table_Row
     */
    public static function load($row, $reset);
    
    
    /**
     * Set the table object
     * 
     * @param Zend_Db_Table $table
     * 
     * @return SG_Db_Model_Interface
     */
    public function setTable(Zend_Db_Table $table);
    
    /**
     * Get the table object
     * 
     * @param void
     * 
     * @return Zend_Db_Table
     */
    public function getTable();
    
    /**
     * Set the table class name
     * 
     * @param string $tableClass
     * 
     * @return SG_Db_Model_Interface
     */
    public function setTableClass($tableClass);
    
    /**
     * Get the table class name
     * 
     * @param void
     * 
     * @return string
     */
    public function getTableClass();
}