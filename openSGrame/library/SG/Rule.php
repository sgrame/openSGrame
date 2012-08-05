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
 * SG_Rule
 *
 * A Rule is a collection formulas and params
 *
 * Extends the EasyBib_Form for Twitter Bootstrap integration
 *
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Rule
{
    /**
     * A param or formula
     * 
     * @var rule
     */
    protected $_rule;
    
    /**
     * The constructor
     * 
     * @param mixed
     * 
     * @return SG_Rule
     */
    public function __construct($rule)
    {
        $this->_rule = $rule;
    }
    
    /**
     * Check the rule
     * 
     * @param SG_Rule_Variables
     * 
     * @return bool
     */
    public function isValid(SG_Rule_Variables $variables)
    {
        if($this->_rule instanceof SG_Rule_Param_Abstract) {
            return (bool)$this->_rule->getValue($variables);
        }
        elseif($this->_rule instanceof SG_Rule_Function_Abstract) {
            return (bool)$this->_rule->getResult($variables);
        }
        
        throw new SG_Rule_Exception('The rule is not supported');
    }
    
    /**
     * Convert to string
     * 
     * @param void
     * 
     * @return string 
     */
    public function __toString()
    {
        return (string)$this->_rule;
    }
}

