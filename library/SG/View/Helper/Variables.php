<?php
/**
 * @category SG
 * @package  View
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_View_Helper_Variable
 *
 * Helper to get a variable from the SG_Variable collection
 *
 * @category SG
 * @package  View
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_View_Helper_Variables extends Zend_View_Helper_Abstract 
{
    /**
     * Variable container
     * 
     * @var SG_Variable
     */
    protected $_variables;
    
    /**
     * Constructor
     * 
     * @param void
     * 
     * @return void
     */
    public function __construct()
    {
        $this->_variables = SG_Variables::getInstance();
    }
  
    /**
     * Get a variable from the SG_Variable storage
     *
     * @param string 
     *     Variable key
     * @param mixed
     *     (optional) the default value if the variable does not exists
     * 
     * @return mixed
     */
    public function variables($key, $default = null)
    {
        return $this->_variables->get($key, $default);
    }
}