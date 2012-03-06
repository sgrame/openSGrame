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
 * SG_View_Helper_Acl
 *
 * Helper to access the SG_Acl class
 *
 * @category SG
 * @package  View
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_View_Helper_Acl extends Zend_View_Helper_Abstract 
{
    /**
     * Authentication container
     * 
     * @var SG_Acl
     */
    protected $_acl;
    
    /**
     * Constructor
     * 
     * @param void
     * 
     * @return void
     */
    public function __construct()
    {
        $this->_acl = Zend_Registry::get('acl');
    }
  
    /**
     * Get the auth object
     *
     * @param void
     * 
     * @return User_Model_Row_User
     */
    public function acl()
    {
        return $this->_acl;
    }
}