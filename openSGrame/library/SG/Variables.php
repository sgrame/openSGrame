<?php
/**
 * @category SG
 * @package  Variables
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Variables
 *
 * Wrapper around storing variables in a central storage (Database) location
 * and caching mechanism for faster response
 *
 * @category SG
 * @package  Variables
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Variables
{
    /**
     * Singleton instance
     *
     * Marked only as protected to allow extension of the class. To extend,
     * simply override {@link getInstance()}.
     *
     * @var SG_Variables
     */
    protected static $_instance = null;
  
    /**
     * Static storage so it needs only to be loaded once
     * 
     * @var array
     */
    protected $_variables = array();
    
    /**
     * Is the storage loaded
     * 
     * @var bool
     */
    protected $_loaded = false;
    
    /**
     * Database connection to use
     * 
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db;
    
    /**
     * The table name
     * 
     * @var string
     */
    protected $_table = 'variable';
  
    /**
     * Constructor
     *
     * Instantiate using {@link getInstance()}; variables is a singleton
     * object.
     *
     * @return void
     */
    protected function __construct($db = null)
    {
        if(!($db instanceof Zend_Db_Adapter_Abstract)) {
            $db = Zend_Db_Table::getDefaultAdapter();
        }
        
        if(!$db) {
            throw new SG_Variables_Exception(
                'No database adapter available to access the variables table'
            );
        }
        
        $this->_db = $db;
    }

    /**
     * Enforce singleton; disallow cloning
     *
     * @return void
     */
    private function __clone()
    {
    }
    
    /**
     * Singleton instance
     *
     * @param Zend_Db_Adapter_Abstract
     *     (optional) Database adapter to use
     * 
     * @return SG_Variables
     */
    public static function getInstance($db = null)
    {
        if (null === self::$_instance) {
            self::$_instance = new self($db);
        }

        return self::$_instance;
    }
    
    /**
     * Get a variable by its name
     * 
     * @param string Variable name
     * @param mixed  (optional) default value if variable is not in storage
     * 
     * @return mixed
     */
    public function get($name, $default = null)
    {
        if(!$this->exists($name)) {
            return $default;
        }
        
        return $this->_variables[$name];
    }
    
    /**
     * Set a variable by its name
     * 
     * @param string Variable name
     * @param mixed  Value
     */
    public function set($name, $value)
    {
        $data = array(
            'value' => serialize($value),
        );
        $whereName = $this->_db->quote($name);
        $where = 'name = ' . $whereName;
      
        if(!$this->exists($name)) {
            $data['name'] = $name;
            $this->_db->insert($this->_table, $data);
        }
        else {
            $this->_db->update($this->_table, $data, $where);
        }
        
        $this->_loadVariables(true);
    }
    
    /**
     * Remove a variable from the storage
     * 
     * @param string Variable name
     * 
     * @return bool
     */
    public function remove($name)
    {
        if(!$this->exists($name)) {
            return false;
        }
        
        $name = $this->_db->quote($name);
        $this->_db->delete($this->_table, 'name = ' . $name);
        
        $this->_loadVariables(true);
    }
    
    /**
     * Check if variable exists
     * 
     * @param string
     * 
     * @return bool
     */
    public function exists($name)
    {
        if ($name === '') {
            throw new SG_Variables_Exception(
                'The ' . $name . ' key must be a non-empty string'
            );
        }
        
        $this->_loadVariables();
        return isset($this->_variables[$name]);
    }
    
    /**
     * Force reload of the variables
     * 
     * @param void
     * 
     * @return $this
     */
    public function reload()
    {
        $this->_variables = array();
        $this->_loaded    = false;
    }
    
    /**
     * Load the storage array (if not already done)
     * 
     * @param bool $reset force reloading from the database table
     */
    protected function _loadVariables($reset = false)
    {
        if($reset || !$this->_loaded) {
            $this->reload();
          
            // !TODO: store/load from cache
            $table     = $this->_db->quoteIdentifier($this->_table);
            $variables = $this->_db->fetchAll('SELECT * FROM ' . $table);
            
            foreach($variables AS $variable) {
                $this->_variables[$variable['name']] = unserialize($variable['value']);
            }
            $this->_loaded = true;
        }
    }
    
    /**
     * __get() - method to get a variable by its name
     *
     * @param string $name - programmatic name of a key
     * @return mixed
     * 
     * @throws SG_Variables_Exception
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * __set() - method to set a variable/value in this storage
     *
     * @param string $name - programmatic name of a key
     * @param mixed $value - value
     * 
     * @return true
     * 
     * @throws SG_Variables_Exception
     */
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }
    
    /**
     * __isset() - determine if a variable in this store is set
     *
     * @param string $name - programmatic name of a key
     * 
     * @return bool
     */
    public function __isset($name)
    {
        return $this->exists($name);
    }

    /**
     * __unset() - unset a variable in this object's namespace.
     *
     * @param string $name - programmatic name of a key, in a <key,value> pair in the current namespace
     * @return true
     */
    public function __unset($name)
    {
        return $this->remove($name);
    }
}
