<?php
/* SVN FILE $Id: Db.php 15 2010-06-24 22:43:40Z SerialGraphics $ */
/**
 * @filesource
 * @copyright		Serial Graphics Copyright 2010
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			Jun 23, 2010
 */

/**
 * Log writer to database.
 * 
 * This logger writes the log data by default to the log table.
 * It adds auto the current logged in user and the date-time to the log.
 * 
 * @category   SG
 * @package    SG_Log
 * @subpackage Writer
 */
class SG_Log_Writer_Db extends Zend_Log_Writer_Db
{
    /**
     * Database adapter instance
     * @var Zend_Db_Adapter
     */
    private $_db;

    /**
     * Name of the log table in the database
     * @var SG_Db_Table
     */
    private $_table;

    /**
     * Relates database columns names to log data field keys.
     *
     * @var null|array
     */
    private $_columnMap;

    /**
     * Class constructor
     *
     * @param Zend_Db_Adapter $db   Database adapter instance
     * @param string $table         Log table in database
     * @param array $columnMap
     */
    public function __construct($db = null, $table = 'log', $columnMap = null)
    {
    	$this->_db = (is_null($db))
    		? SG_Db_Table::getDefaultAdapter()
    		: $db;
    		
        $this->_columnMap = $columnMap;	
        
        // Init the table mapper
        $this->_table = new SG_Db_Table(array(
        	'db' => $db, 
        	'name' => $table
        ));
    }

    /**
     * Write a message to the log.
     *
     * @param  array  $event  event data
     * @return void
     */
    protected function _write($event)
    {
        if ($this->_db === null) {
            require_once 'Zend/Log/Exception.php';
            throw new Zend_Log_Exception('Database adapter is null');
        }
        
        // add the controller, action and the parameters to the event data
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $event['uri']			= $request->getRequestUri();
        $event['module'] 		= $request->getModuleName();
        $event['controller'] 	= $request->getControllerName();
        $event['action'] 		= $request->getActionName();
        
        // map the data
        if ($this->_columnMap === null) {
            $dataToInsert = $event;
        } else {
            $dataToInsert = array();
            foreach ($this->_columnMap as $columnName => $fieldKey) {
                $dataToInsert[$columnName] = $event[$fieldKey];
            }
        }
        
        // save the row
        $row = $this->_table->createRow($dataToInsert);
        $row->save();
    }
}
