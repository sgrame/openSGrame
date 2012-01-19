<?php
/* SVN FILE $Id: Log.php 15 2010-06-24 22:43:40Z SerialGraphics $ */
/**
 * @filesource
 * @copyright		Serial Graphics Copyright 2010
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			Jun 23, 2010
 */

/**
 * Singleton to log message to the log
 */
class SG_Log
{
	/**
	 * Priority leves
	 * 
	 * @var string
	 */
	const EMERG   = 0;  // Emergency: system is unusable
    const ALERT   = 1;  // Alert: action must be taken immediately
    const CRIT    = 2;  // Critical: critical conditions
    const ERR     = 3;  // Error: error conditions
    const WARN    = 4;  // Warning: warning conditions
    const NOTICE  = 5;  // Notice: normal but significant condition
    const INFO    = 6;  // Informational: informational messages
    const DEBUG   = 7;  // Debug: debug messages
	
	/**
	 * The log to write to
	 * 
	 * @var	Zend_Log
	 */
	static $_log;
	
	/**
     * Log a message at a priority
     *
     * @param  string   $message   Message to log
     * @param  integer  $priority  Priority of message
     * @param  mixed    $extras    Extra information to log in event
     * @return void
     * @throws Zend_Log_Exception
     */
    static function log($message, $priority = self::INFO, $extras = null)
    {
    	if(is_null(self::$_log))
    	{
    		self::$_log = Zend_Registry::get('SG_Logger');
    	}
    	
    	self::$_log->log($message, $priority, $extras = null);
    }
}