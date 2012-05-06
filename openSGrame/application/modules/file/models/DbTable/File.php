<?php
/**
 * @category File_Model
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * File_Model_DbTable_File
 *
 * File mapper
 *
 * @category File_Model
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class File_Model_DbTable_File extends SG_Db_Table
{
    /**
     * Table name
     */
    protected $_name = 'file';
    
    /**
     * Row class
     */
    protected $_rowClass = 'File_Model_Row_File';
    
    /**
     * Whether or not this table should use cr records 
     * when updating or deleting
     * 
     * @var bool
     */
    protected $_contingency = false;
    
    
    /**
     * Find by the filename
     * 
     * @param string $filename
     * 
     * @return Zend_Db_Table_Rowset
     */
    public function findByFilename($filename)
    {
        $select = $this->select();
        $select->where('filename = ?', $filename);
        return $this->fetchAll($select);
    }
    
    /**
     * Find by the uri
     * 
     * @param string $uri
     * 
     * @return Zend_Db_Table_Rowset 
     */
    public function findByUri($uri)
    {
        $select = $this->select();
        $select->where('uri = ?', $uri);
        return $this->fetchAll($select);
    }
    
    /**
     * Find temporary file records
     * 
     * @param string $date
     *    (optional) The date in date string format (Y-m-d H:i:s)
     * 
     * @return Zend_Db_Table_Rowset
     */
    public function findTemporary($date = null)
    {
        $select = $this->select();
        $select->where('status = 0');
        if (!is_null($date)) {
            $select->where('cd < ?', $date);
        }
        return $this->fetchAll($select);
    }
    
    /**
     * Delete all temporary records (older then given date)
     * 
     * @param string $date
     *     (optional) The date in date string format (Y-m-d H:i:s)
     * 
     * @return int
     *     The number of deleted records 
     */
    public function deleteTemporary($date = null)
    {
        $where = array();
        $where[] = 'status <> 1';
        
        if (!is_null($date)) {
            $where[] = $this->getAdapter()->quoteInto('cd < ?', $date);
        }
        
        $result = $this->delete($where);
        return $result;
    }
}
