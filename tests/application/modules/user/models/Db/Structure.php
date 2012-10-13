<?php
/**
 * Database structures for the User module
 * 
 * Will be used for in-memory SQLite database testing
 */
class User_Model_Db_Structure
{
    /**
     * Create all the tables
     */
    static function createAllTables($db)
    {
        self::createUserTable($db);
        self::createUserGroupTable($db);
        self::createUserGroupsTable($db);
    }
    
    /**
     * Create the user table
     */
    static function createUserTable($db)
    {
        $db->query('BEGIN;');
        $db->query("
            CREATE TABLE `user` (
                `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                `username` varchar(128) NOT NULL,
                `firstname` varchar(128) DEFAULT NULL,
                `lastname` varchar(128) DEFAULT NULL,
                `email` varchar(256) NOT NULL,
                `password` varchar(32) NOT NULL,
                `password_salt` varchar(32) NOT NULL,
                `locked` tinyint(1) NOT NULL DEFAULT '0',
                `blocked` tinyint(1) NOT NULL DEFAULT '0',
                `picture_id` int(11) DEFAULT NULL,
                `last_access` datetime DEFAULT NULL,
                `last_login` datetime DEFAULT NULL,
                `created` datetime NOT NULL,
                `ci` int(11) NOT NULL DEFAULT '0',
                `cd` datetime DEFAULT '0000-00-00 00:00:00',
                `cr` int(11)  DEFAULT NULL
            );
        ");
        $db->query('COMMIT;');
    }
    
    /**
     * Create the group table
     */
    static function createUserGroupTable($db)
    {
        $db->query('BEGIN;');
        $db->query("
            CREATE TABLE `user_group` (
                `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                `name` varchar(128) NOT NULL,
                `ci` int(11) NOT NULL DEFAULT '0',
                `cd` datetime DEFAULT '0000-00-00 00:00:00',
                `cr` int(11) DEFAULT NULL
            );
        ");
        $db->query('COMMIT;');
    }
    
    /**
     * Create the groups table
     */
    static function createUserGroupsTable($db)
    {
        $db->query('BEGIN;');
        $db->query("
            CREATE TABLE `user_groups` (
                `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                `user_id` int(11) NOT NULL,
                `group_id` int(11) NOT NULL,
                `ci` int(11) NOT NULL DEFAULT '0',
                `cd` datetime DEFAULT '0000-00-00 00:00:00',
                `cr` int(11) DEFAULT NULL
            );
        ");
        $db->query('COMMIT;');
    }
    
    
    
    
    
    /**
     * Populate all tables
     */
    static function populateAllTables($db) 
    {
        self::populateUserTable($db);
        self::populateGroupTable($db);
        self::populateGroupsTable($db);
    }
    
    /**
     * Populate the user table
     */
    static function populateUserTable($db)
    {
        $db->query("
            INSERT INTO `user` VALUES
                (0, 'system', NULL, NULL, 'system@opensgrame.org', 'fsgqgfwf dshgdfvqwv', 'xbghgfdbvgwgr', 0, 0, NULL, NULL, NULL, '2012-03-06 21:43:00', 1, '2012-03-06 21:43:00', NULL)
        ");
        $db->query("
            INSERT INTO `user` VALUES
                (1, 'admin', NULL, NULL, 'admin@opensgrame.org', 'd5a7bc2f2aec914b3fa7ce74c0da2f05', '7sDxrsiao7JQpeaLa4pZ3ghxHerx4gsc', 0, 0, NULL, NULL, NULL, '2012-03-06 21:43:00', 1, '2012-10-03 09:43:53', NULL)
        ");
        $db->query("
            INSERT INTO `user` VALUES
                (2, 'user1-1', 'User', 'OneOne', 'user.one-one@opensgrame.org', 'b1f5d00a78742ab459b76e1a21d8a144', 'tXbmnkudLDTQQpffXbx90NpCJfSanjAR', 0, 0, NULL, NULL, NULL, '2012-03-20 23:18:05', 1, '2012-06-26 00:09:42', NULL)
        ");
        $db->query("
            INSERT INTO `user` VALUES
                (3, 'user2-1', 'User', 'TwoOne', 'user.two-one@opensgrame.org', '1a2583e71407abc24fba61d685f6d8ec', 'DtiUJBhuPMdQD0W9Nofyzsn5D0MKiGMY', 0, 0, NULL, NULL, NULL, '2012-03-20 23:19:23', 1, '2012-07-31 13:58:09', NULL)
        ");
        $db->query("
            INSERT INTO `user` VALUES
                (4, 'user2-2', 'User', 'TwoOne', 'user.two-two@opensgrame.org', '1a2583e71407abc24fba61d685f6d8ec', 'DtiUJBhuPMdQD0W9Nofyzsn5D0MKiGMY', 0, 0, NULL, NULL, NULL, '2012-03-20 23:19:23', 1, '2012-07-31 13:58:09', NULL)
        ");
    }
    
    /**
     * Populate the group table
     */
    static function populateGroupTable($db)
    {
        $db->query("
            INSERT INTO `user_group` VALUES
                (1, 'system',  1, '2012-06-01 12:00:00', NULL)
            ;
        ");
        $db->query("
            INSERT INTO `user_group` VALUES
                (2, 'Group 1', 1, '2012-06-02 12:00:00', NULL)
            ;
        ");
        $db->query("
            INSERT INTO `user_group` VALUES
                (3, 'Group 2', 1, '2012-06-03 12:00:00', NULL)
            ;
        ");
    }
    
    /**
     * Populate the user-group table
     */
    static function populateGroupsTable($db)
    {
        $db->query("
            INSERT INTO `user_groups` VALUES
                (1, 1, 1, 0, '2012-06-01 12:00:00', NULL)
            ;
        ");
        $db->query("
            INSERT INTO `user_groups` VALUES
                (2, 2, 2, 1, '2012-06-02 12:00:00', NULL)
            ;
        ");
        $db->query("
            INSERT INTO `user_groups` VALUES
                (3, 3, 3, 1, '2012-06-03 12:00:00', NULL)
            ;
        ");
        $db->query("
            INSERT INTO `user_groups` VALUES
                (4, 4, 3, 1, '2012-06-03 12:00:00', NULL)
            ;
        ");
    }
}