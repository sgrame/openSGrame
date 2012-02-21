<?php
/**
 * @category SG
 * @package  Log
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Log
 *
 * Logger to write events to the Log table
 *
 * @category SG
 * @package  Log
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Log
{
    /**
     * Priority levels
     * 
     * @var string
     */
    const EMERGENCY = 0; // Emergency: system is unusable
    const EMERG     = 0; // Emergency: system is unusable
    const ALERT     = 1;  // Alert: action must be taken immediately
    const CRITICAL  = 2;  // Critical: critical conditions
    const CRIT      = 2;  // Critical: critical conditions
    const ERROR     = 3;  // Error: error conditions
    const ERR       = 3;  // Error: error conditions
    const WARNING   = 4;  // Warning: warning conditions
    const WARN      = 4;  // Warning: warning conditions
    const NOTICE    = 5;  // Notice: normal but significant condition
    const INFO      = 6;  // Informational: informational messages
    const DEBUG     = 7;  // Debug: debug messages
    
    /**
     * The log to write to
     * 
     * @var    Zend_Log
     */
    static $_log;
    
    /**
     * Log a message at a priority
     *
     * @param string $type      
     *     Message type. used to filter the log messages
     * @param string $message   
     *     Message to log
     * @param integer $priority
     *     Priority of message
     * @param mixed $extras
     *     Extra information to log in event
     * 
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