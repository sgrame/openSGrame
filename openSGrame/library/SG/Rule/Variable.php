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
 * SG_Rule_Variable
 *
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Rule_Variable extends SG_Rule_Abstract
{
    /**
     * The variable key
     * 
     * @var string
     */
    protected $_key;
    
    /**
     * Constructor
     * 
     * @param string $key
     * @param SG_Rule_Variables $variables
     * 
     * @return SG_Rule_Variable
     */
    public function __construct($key = NULL, $variables = null)
    {
        if(!is_null($key)) {
            $this->setKey($key);
        }
        if(!is_null($variables)) {
            parent::__construct(array('variables' => $variables));
        }
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
        $variables = $this->getVariables();
        if(!$variables) {
            return NULL;
        }
        
        return $variables->getValue($this->getKey());
    }
    
    /**
     * Set the key value
     * 
     * @param string $key
     * 
     * @return SG_Rules_Variable
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

