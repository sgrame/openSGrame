<?php
/**
 * Database structures for the User module
 * 
 * Will be used for in-memory SQLite database testing
 */
class Activity_Model_Db_Structure
{
    /**
     * Create all the tables
     */
    static function createAllTables($db)
    {
        self::createActivityTable($db);
    }
    
    /**
     * Create te user table
     */
    static function createActivityTable($db)
    {
        // create the activity table
        $db->query('BEGIN;');
        $db->query(
            "CREATE TABLE IF NOT EXISTS `activity` (
                `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                `module` varchar(32) DEFAULT NULL,
                `type` varchar(32) DEFAULT NULL,
                `params` longtext,
                `owner_id` int(11) NOT NULL DEFAULT '0',
                `created` datetime DEFAULT '0000-00-00 00:00:00',
                `ci` int(11) NOT NULL DEFAULT '0',
                `cd` datetime DEFAULT '0000-00-00 00:00:00',
                `cr` int(11) DEFAULT NULL
            )"
        );
        $db->query('COMMIT;');
    }
}