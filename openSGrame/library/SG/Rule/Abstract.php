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
 * SG_Rule_Abstract
 * 
 * This base class contains the logic to set and get the variables collection
 *
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
abstract class SG_Rule_Abstract
{
    /**
     * The variables collection
     * 
     * @var SG_Rule_Variables
     */
    protected $_variables;
    
    /**
     * Constructor
     * 
     * @param array $options
     */
    public function __construct($options = array())
    {
        if(!is_array($options) || !isset($options['variables'])) {
            return;
        }
        
        $this->setVariables($options['variables']);
    }
    
    /**
     * Set the variables collection
     * 
     * @param SG_Rule_Variables $variables
     * 
     * @return SG_Rule_Abstract
     */
    public function setVariables(SG_Rule_Variables $variables)
    {
        $this->_variables = $variables;
        return $this;
    }
    
    /**
     * Get tha variables
     * 
     * @param void
     * 
     * @return SG_Rule_Variables
     */
    public function getVariables()
    {
        return $this->_variables;
    }
}

