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
 * SG_Rule_Param
 *
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Rule_Param extends SG_Rule_Param_Abstract
{
    /**
     * The variable key
     * 
     * @var string
     */
    protected $_value;
    
    /**
     * Constructor
     * 
     * @param string $key
     * @param array $options
     * 
     * @return SG_Rule_Variable
     */
    public function __construct($value = NULL)
    {
        $this->setValue($value);
    }
    
    /**
     * Get the value
     * 
     * @param void
     * 
     * @return mixed
     */
    public function getValue()
    {
        return $this->_value;
    }
    
    /**
     * Set the value
     * 
     * @param mixed
     * 
     * @return SG_Rule_Value
     */
    public function setValue($value)
    {
        $this->_value = $value;
        return $this;
    }
}

