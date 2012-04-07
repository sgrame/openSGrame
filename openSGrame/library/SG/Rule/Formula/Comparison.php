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
 * SG_Rule_Formula_Abstract
 * 
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Rule_Formula_Comparison extends SG_Rule_Formula_Abstract
{
    /**
     * Possible operators
     * 
     * @var string
     */
    const EQUAL             = '=';
    const NOT_EQUAL         = '<>';
    const GREATHER          = '>';
    const GREATHER_OR_EQUAL = '>=';
    const LESS              = '<';
    const LESS_OR_EQUAL     = '<=';
  
    /**
     * Param left of the comparison function
     * 
     * @var SG_Rule_Param_Abstract
     */
    protected $_left;
    
    /**
     * Param right of the comparison function
     * 
     * @var SG_Rule_Param_Abstract
     */
    protected $_right;
    
    /**
     * Comparison operator
     * 
     * @var string
     */
    protected $_operator;
    
    /**
     * Constructor
     * 
     * @param SG_Rule_Param_Abstract $left
     * @param string operator
     * @param SG_Rule_Param_Abstract $right
     * 
     * @return SG_Rule_Formula_Comparison
     */
    public function __construct($left, $operator, $right)
    {
        $this->setLeft($left);
        $this->setOperator($operator);
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
        $left  = $this->_left->getValue($variables);
        $right = $this->_right->getValue($variables);
        
        switch($this->_operator) {
            case self::EQUAL:
                return ($left == $right);
                break;
            case self::NOT_EQUAL:
                return ($left != $right);
                break;
            case self::GREATHER:
                return ($left > $right);
                break;
            case self::GREATHER_OR_EQUAL:
                return ($left >= $right);
                break;
            case self::LESS:
                return ($left < $right);
                break;
            case self::LESS_OR_EQUAL:
                return ($left <= $right);
                break;
        }
        
        throw new SG_Rule_Exception('Operator not supported');
    }
    
    /**
     * Set the left param
     * 
     * @param SG_Rule_Param_Abstract
     * 
     * @return SG_Rule_Formula_Comparison
     */
    public function setLeft(SG_Rule_Param_Abstract $left)
    {
        $this->_left = $left;
        return $this;
    }
    
    /**
     * Get the left param
     * 
     * @param void
     * 
     * @return SG_Rule_Param_Abstract
     */
    public function getLeft()
    {
        return $this->_left;
    }
    
    /**
     * Set the right param
     * 
     * @param SG_Rule_Param_Abstract
     * 
     * @return SG_Rule_Formula_Comparison
     */
    public function setRight(SG_Rule_Param_Abstract $right)
    {
        $this->_right = $right;
        return $this;
    }
    
    /**
     * Get the right param
     * 
     * @param void
     * 
     * @return SG_Rule_Param_Abstract
     */
    public function getRight()
    {
        return $this->_right;
    }
    
    /**
     * Set the operator
     * 
     * @param string
     * 
     * @return SG_Rule_Formula_Comparison
     */
    public function setOperator($operator)
    {
        $this->_operator = $operator;
        return $this;
    }
    
    /**
     * Get the operator
     * 
     * @param void
     * 
     * @return string
     */
    public function getOperator()
    {
        return $this->_operator;
    }
}

