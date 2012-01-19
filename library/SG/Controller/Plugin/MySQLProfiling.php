<?php
/* SVN FILE $Id: MySQLProfiling.php 43 2010-09-12 10:35:20Z SerialGraphics $ */
/**
 * @filesource
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			Aug 18, 2010
 */

/**
 * MySQL Profiling plugin
 *
 * Enables the profiling feature on MySQL server, queries it and
 * retrieves the nescesary data for each individual query.
 *
 * @category	SG
 * @package		SG_Controller
 * @subpackage	SG_Controller_Plugin
 * @author		Pablo Viquez <pviquez@pabloviquez.com>
 * @see			http://www.pabloviquez.com/2010/08/mysql-profiling-and-zend-framework/
 */
class SG_Controller_Plugin_MySQLProfiling
    extends Zend_Controller_Plugin_Abstract
{
    /**
     * Constant to use to diferentiate the queries that dont require
     * profiling
     *
     * @var string
     */
    const DONT_PROFILE = '/*DN_PROFILE*/ ';

    /**
     * Predispatch loop.
     *
     * Is called before an action is dispatched by the dispatcher. This
     * callback allows for proxy or filter behavior. By altering the
     * request and resetting its dispatched flag (via
     * Zend_Controller_Request_Abstract::setDispatched(false)), the
     * current action may be skipped and/or replaced.
     *
     * @return null
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        // Init the profiler
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->query(self::DONT_PROFILE . 'set profiling=1');
        $db->query(self::DONT_PROFILE . 'set profiling_history_size=100');
    }

    /**
     * Postdispatch Loop.
     *
     * Is called after an action is dispatched by the dispatcher. This
     * callback allows for proxy or filter behavior.
     */
    public function postDispatch(
        Zend_Controller_Request_Abstract $request)
    {
        $columns =
            array(
                'Query ID',
                'Duration',
                'Executed Query');

        $profiles =
            $this->_constructFirePhp(
                $columns,
                'MySQL Profiling - Profiles');

        $columns =
            array(
                'Query ID',
                'SEQ',
                'State',
                'numb ops',
                'Total duration',
                'Avg duration',
                'Total CPU',
                'Avg CPU',
                'Avg CPU Sys',
                'Block Ops In',
                'Block Ops Out');

        // Do the MySQL profiling
        $queryIds = $this->_setProfiles($profiles);

        // Get the profiling for each individual query
        foreach ($queryIds as $id) {
            $queriesProfile =
                $this->_constructFirePhp(
                    $columns,
                    "MySQL Profiling - Query ID: {$id}");
            $this->_getProfileForQueryId($queriesProfile, $id);
        }
    }

    /**
     * Retrieves the query profile for each indidividual query
     *
     * @param Zend_Wildfire_Plugin_FirePhp_TableMessage $profiles
     * @param int $queryId
     * @return void
     */
    private function _getProfileForQueryId(
        Zend_Wildfire_Plugin_FirePhp_TableMessage $profiles,
        $queryId)
    {
        if (!ctype_digit((string)$queryId)) {
            return;
        }

        $query = self::DONT_PROFILE .
            "SELECT {$queryId},
                    MIN(seq),
                    state,
                    COUNT(*),
                    ROUND(SUM(duration),5),
                    ROUND(AVG(duration),5),
                    ROUND(SUM(cpu_user),5),
                    ROUND(AVG(cpu_user),5),
                    ROUND(AVG(cpu_system),5) ,
                    ROUND(AVG(block_ops_in),5),
                    ROUND(AVG(block_ops_out),5)
               FROM information_schema.profiling
              WHERE query_id = {$queryId}
           GROUP BY state
           ORDER BY seq";

        $db = Zend_Db_Table::getDefaultAdapter();
        $sth = $db->query($query);
        $rows = $sth->fetchAll();

        foreach($rows as $row) {
            $values = array_values($row);
            $profiles->addRow($values);
        }
    }

    /**
     * Constructs and send the profiles table.
     *
     * @param Zend_Wildfire_Plugin_FirePhp_TableMessage $profiles
     * @return array Retrieves an array with the queries profiles
     */
    private function _setProfiles(
        Zend_Wildfire_Plugin_FirePhp_TableMessage $profiles)
    {
        // Get the profiles
        $db = Zend_Db_Table::getDefaultAdapter();
        $sth = $db->query(self::DONT_PROFILE . 'show profiles');
        $rows = $sth->fetchAll();

        $queryIds = array();
        foreach($rows as $row) {
            // Profile only queries marked as profile
            if (!(strpos($row['Query'], self::DONT_PROFILE) === false)) {
                continue;
            }

            $queryIds[] = $row['Query_ID'];
            $values = array_values($row);
            $profiles->addRow($values);
        }

        return $queryIds;
    }

    /**
     * Constructs the FirePHP messages.
     *
     * @param array $profiles
     * @param string $label
     * @return Zend_Wildfire_Plugin_FirePhp_TableMessage
     */
    private function _constructFirePhp(
        array $columns,
        $label,
        $setBuffer = true)
    {
        $msg = new Zend_Wildfire_Plugin_FirePhp_TableMessage($label);

        // If a message is buffered it can be updated for the duration
        // of the request and is only flushed at the end of the request.
        $msg->setBuffered($setBuffer);
        $msg->setHeader($columns);

        // Destroy the message to prevent delivery
        $msg->setOption('includeLineNumbers', false);
        Zend_Wildfire_Plugin_FirePhp::getInstance()->send($msg);
        return $msg;
    }
}
