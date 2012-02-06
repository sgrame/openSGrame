<?php
/**
 * @category SG_Auth
 * @package  Adapter
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Auth_Adapter_DbTable
 * 
 * Database authentication adapter with CR record support
 *
 * @category TB
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Auth_Adapter_DbTable extends Zend_Auth_Adapter_DbTable
{
    /**
     * getDbSelect()
     * 
     * Return the preauthentication Db Select object for 
     * userland select query modification
     *
     * @return Zend_Db_Select
     */
    public function getDbSelect()
    {
        if ($this->_dbSelect == null) {
            $this->_dbSelect = $this->_zendDb->select();
            $this->_dbSelect->where('cr IS NULL');
        }
        
        return $this->_dbSelect;
    }
}
