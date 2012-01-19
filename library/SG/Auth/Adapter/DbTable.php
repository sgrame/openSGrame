<?php
/* SVN FILE $Id: DbTable.php 2 2010-06-14 08:04:19Z SerialGraphics $ */
/**
 * Auth adapter with CR support
 *
 * @filesource
 * @copyright		Serial Graphics Copyright 2009
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			Aug 7, 2009
 */


/**
 * Auth adapter with CR support
 */
class SG_Auth_Adapter_DbTable extends Zend_Auth_Adapter_DbTable
{
    /**
     * getDbSelect() - Return the preauthentication Db Select object for userland select query modification
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
