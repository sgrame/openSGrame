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
 * SG_Rule_Param_Variable
 * 
 * Param from the variables container
 *
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Rule_Param_Variable extends SG_Rule_Param_Abstract
{
    /**
     * The variables key
     * 
     * @var string
     */
    protected $_key;
    
    /**
     * Constructor
     * 
     * @param string $key
     * 
     * @return SG_Rule_Param_Variable
     */
    public function __construct($key = NULL)
    {
        if(!is_null($key)) {
            $this->setKey($key);
        }
    }
    
    /**
     * Get the value
     * 
     * @param SG_Rule_Variables $variables
     * 
     * @return mixed
     */
    public function getValue(SG_Rule_Variables $variables)
    {
        return $variables->getValue($this->getKey());
    }
    
    /**
     * Set the key value
     * 
     * @param string $key
     * 
     * @return SG_Rule_Param_Variable
     */
    public function setKey($key)
    {
        $this->_key = $key;
        return $this;
    }
    
    /**
     * Get the key value
     * 
     * @param void
     * 
     * @return string
     */
    public function getKey()
    {
        return $this->_key;
    }
}

