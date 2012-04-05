<?php
/**
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Rule_Variables
 *
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Rule_Variables
{
    /**
     * The variables
     * 
     * @var array
     */
    protected $_variables;
  
    /**
     * Constructor
     * 
     * @param array $variables
     * 
     * @return SG_Rule_Variables
     */
    public function __construct($variables = array())
    {
        $this->setVariables($variables);
    }
    
    /**
     * Get a value from the variables array by its key
     * 
     * @param string $key
     * @param mixed $default
     *     OPTIONAL default value when the key is not in the variables array
     * 
     * @return mixed
     */
    public function getValue($key, $default = null)
    {
        if(!isset($this->_variables[$key])) {
            return $default;
        }
        
        return $this->_variables[$key];
    }
    
    /**
     * Set a variable by its variables key
     * 
     * @param string $key
     * @param mixed $value
     * 
     * @return SG_Rules_Variables
     */
    public function setValue($key, $value)
    {
        $this->_variables[$key] = $value;
        return $this;
    }
    
    /**
     * Add a variable
     * 
     * @param string $key
     * @param mixed $value
     * 
     * @return SG_Rules_Variables
     */
    public function addVariable($key, $value)
    {
        return $this->setValue($key, $value);
    }
    
    /**
     * Sets all the variables
     * 
     * @param array $variables
     * 
     * @return SG_Rules_Variables
     */
    public function setVariables($variables)
    {
        if(!is_array($variables)) {
            throw new SG_Rule_Exception('The variables needs to be an array');
        }
        
        $this->_variables = $variables;
        return $this;
    }
    
    /**
     * Get the variables
     * 
     * @param void
     * 
     * @return array
     */
    public function getVariables()
    {
        return $this->_variables;
    }
}

