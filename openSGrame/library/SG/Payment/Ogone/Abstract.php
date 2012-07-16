<?php
/**
 * @category SG
 * @package  Payment
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Payment_Ogone_Abstract
 * 
 * Wrapper around the Ogone payment service
 *
 * @category SG
 * @package  Payment
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Payment_Ogone_Abstract
{
    /**
     * Working modes
     * 
     * @var string 
     */
    const MODE_PRODUCTION = 'production';
    const MODE_TEST       = 'test';
    
    
    /**
     * Settings
     * 
     * @var array 
     */
    protected $_settings = array(
        'mode'        => self::MODE_PRODUCTION,
        // SHA IN protection
        'encryption'  => 'SHA-1',
        'passphrase'  => null,
        
    );
    
    /**
     * The data
     * 
     * Add all the possible columnNames here as array keys in UPPERCASE.
     * This will be the only columnNames allowed to add data to the object.
     * 
     * @var array
     */
    protected $_data = array();
    
    /**
     * Tracks columns where data has been updated. 
     * 
     * Allows lighter data retrieval
     *
     * @var array
     */
    protected $_modifiedFields = array();
    
    
    /**
     * Class constructor
     * 
     * @param array $settings 
     */
    public function __construct($settings = array())
    {
        if (!empty($settings) && is_array($settings)) {
            foreach ($settings AS $key => $setting) {
                if (!array_key_exists($key, $this->_settings)) {
                    continue;
                }
                
                $this->_settings[$key] = $setting;
            }
        }
    }
    
    
    /**
     * Retrieve row field value
     *
     * @param  string $columnName 
     *     The user-specified column name.
     * @return string             
     *     The corresponding column value.
     * 
     * @throws SG_Payment_Exception 
     *     If the $columnName is not a column in the data array.
     */
    public function __get($columnName)
    {
        $columnName = $this->_transformColumn($columnName);
        $this->_checkColumnName($columnName);
        return $this->_data[$columnName];
    }

    /**
     * Set row field value
     *
     * @param  string $columnName 
     *     The column key.
     * @param  mixed  $value
     *     The value for the property.
     * 
     * @return void
     * 
     * @throws SG_Payment_Exception 
     *     If the $columnName is not a column in the data array.
     */
    public function __set($columnName, $value)
    {
        $columnName = $this->_transformColumn($columnName);
        $this->_checkColumnName($columnName);
        
        $this->_data[$columnName]           = $value;
        $this->_modifiedFields[$columnName] = true;
    }
    
    /**
     * Unset row field value
     *
     * @param string $columnName 
     *     The column key.
     * 
     * @return SG_Payment_Ogone
     * 
     * @throws SG_Payment_Exception
     */
    public function __unset($columnName)
    {
        $columnName = $this->_transformColumn($columnName);
        $this->_checkColumnName($columnName);
        $this->_data[$columnName] = null;
        if (isset($this->_modifiedFields[$columnName])) {
            unset($this->_modifiedFields[$columnName]);
        }
        return $this;
    }

    /**
     * Test existence of row field
     *
     * @param string $columnName   
     *     The column key.
     * 
     * @return boolean
     */
    public function __isset($columnName)
    {
        $columnName = $this->_transformColumn($columnName);
        return array_key_exists($columnName, $this->_modifiedFields);
    }
    
    /**
     * Transform a column name from the user-specified form
     * to the physical form used in the data array.
     *
     * @param string $columnName 
     *     Column name given.
     * 
     * @return string 
     *     The column name after transformation applied (none by default).
     * 
     * @throws SG_Payment_Exception 
     *     If the $columnName is not a string.
     */
    protected function _transformColumn($columnName)
    {
        if (!is_string($columnName)) {
            throw new Zend_Db_Table_Row_Exception(
                'Specified column is not a string'
            );
        }
        // Column names are in uppercase
        return strtoupper($columnName);
    }
    
    /**
     * Create/Get the SHA hash
     * 
     * @param void
     * 
     * @return string 
     */
    public function getHash()
    {
        $data = $this->getData();
        ksort($data);
        
        $string = array();
        
        foreach ($data AS $fieldname => $value) {
            if ($value === '') {
                continue;
            }
            $string[] = $fieldname 
                . '=' 
                . $value 
                . $this->_settings['passphrase'];
        }
        $string = implode(null, $string);
        
        $hash = null;
        switch (strtoupper($this->_settings['encryption'])) {
            case 'SHA-1':
                $hash = sha1($string);
                break;
            case 'SHA-256':
                $hash = sha256($string);
                break;
            
            default:
                throw new SG_Payment_Exception(
                    'Encryption type "' . $this->_settings['encryption'] . '" not supported' 
                );
        }
        $hash = strtoupper($hash);
        
        return $hash;
    }
    
    /**
     * Get the data array.
     * 
     * @param bool $all
     *     Get all data, by default only modified data
     * 
     * @return array 
     */
    public function getData($all = false)
    {
        if ($all) {
            return $this->_data;
        }
        
        $data = array();
        foreach ($this->_modifiedFields AS $columnName => $changed) {
            if (!$changed) {
                continue;
            }
            $data[$columnName] = $this->_data[$columnName];
        }
        
        return $data;
    }
    
    
    /**
     * Check if the columnname exists within the data array
     * 
     * @param string $fieldname
     * 
     * @return bool
     * 
     * @throws SG_Payment_Exception 
     *     If the $columnName is not a column in the data array.
     */
    protected function _checkColumnName($columnName)
    {
        if (!array_key_exists($columnName, $this->_data)) {
            throw new SG_Payment_Exception(
                "Specified column \"$columnName\" is not in the row"
            );
        }
    }
}

