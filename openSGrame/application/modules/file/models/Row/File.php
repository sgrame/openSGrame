<?php
/**
 * @category File_Model
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * File_Model_Row_File
 *
 * File row
 *
 * @category File_Model
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class File_Model_Row_File extends Zend_Db_Table_Row_Abstract
{
    /**
     * The table class name
     * 
     * @var     string
     */
    protected $_tableClass = 'File_Model_DbTable_File';
}
