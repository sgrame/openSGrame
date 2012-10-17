<?php
/**
 * @category SG_Test
 * @package  PHPUnit
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Test_Db_Adapter_Memory
 *
 * Predefined adapter that sets up an SQLite database in memory
 *
 * @category SG_Test
 * @package  PHPUnit
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Test_Db_Adapter_Memory extends Zend_Db_Adapter_Pdo_Sqlite
{
    /**
     * Constructor.
     *
     * No config needed, the database will be setup automatically in memory
     *
     */
    public function __construct()
    {
        $config = array(
            'dbname' => ':memory:',
        );
        return parent::__construct($config);
    }
}
