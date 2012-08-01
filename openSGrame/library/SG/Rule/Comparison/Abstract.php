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
 * SG_Rule_Function_Comparison_Abstract
 * 
 * Base class for comparison functions
 * 
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
abstract class SG_Rule_Comparison_Abstract extends SG_Rule_Function_Abstract
{
    /**
     * Comparison operator
     * 
     * @var string 
     */
    const OPERATOR = NULL;
    
    /**
     * Param left of the comparison function
     * 
     * @var SG_Rule_Param_Abstract|SG_Rule_Function_Abstract
     */
    protected $_left;
    
    /**
     * Param right of the comparison function
     * 
     * @var SG_Rule_Param_Abstract|SG_Rule_Function_Abstract
     */
    protected $_right;
    
    /**
     * Constructor
     * 
     * @param SG_Rule_Param_Abstract|SG_Rule_Function_Abstract $left
     * @param SG_Rule_Param_Abstract|SG_Rule_Function_Abstract $right
     * 
     * @return SG_Rule_Function_Comparison
     */
    public function __construct($left, $right)
    {
        $this->setLeft($left);
        $this->setRight($right);
    }
  
    /**
     * Get the value
     * 
     * @param SG_Rule_Variables
     * 
     * @return mixed
     */
    public function getResult(SG_Rule_Variables $variables) 
    {
        $left  = $this->_getItemValue($this->_left, $variables);
        $right = $this->_getItemValue($this->_right, $variables);
        
        return $this->_compare($left, $right);
    }
    
    /**
     * Compare the values
     * 
     * @param mixed $left
     * @param mixed $right
     * 
     * @return bool 
     */
    protected function _compare($left, $right)
    {}
    
    /**
     * Set the left param
     * 
     * @param SG_Rule_Param_Abstract|SG_Rule_Function_Abstract
     * 
     * @return SG_Rule_Function_Comparison
     */
    public function setLeft($left)
    {
        $this->_left = $left;
        return $this;
    }
    
    /**
     * Get the left param
     * 
     * @param void
     * 
     * @return SG_Rule_Param_Abstract|SG_Rule_Function_Abstract
     */
    public function getLeft()
    {
        return $this->_left;
    }
    
    /**
     * Set the right param
     * 
     * @param SG_Rule_Param_Abstract|SG_Rule_Function_Abstract
     * 
     * @return SG_Rule_Function_Comparison
     */
    public function setRight($right)
    {
        $this->_right = $right;
        return $this;
    }
    
    /**
     * Get the right param
     * 
     * @param void
     * 
     * @return SG_Rule_Param_Abstract|SG_Rule_Function_Abstract
     */
    public function getRight()
    {
        return $this->_right;
    }
    
    /**
     * Return the string version of the param
     * 
     * @param void
     * 
     * @return string 
     */
    public function __toString() {
        return 
            (string)$this->getLeft() 
            . $this::OPERATOR
            . (string)$this->getRight();
    }
}

