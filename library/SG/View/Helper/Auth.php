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
 * SG_View_Helper_Auth
 *
 * Helper to access the Zend_Auth storage
 *
 * @category SG
 * @package  View
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_View_Helper_Auth extends Zend_View_Helper_Abstract 
{
    /**
     * Authentication container
     * 
     * @var Zend_Auth
     */
    protected $_auth;
    
    /**
     * Constructor
     * 
     * @param void
     * 
     * @return void
     */
    public function __construct()
    {
        $this->_auth = Zend_Auth::getInstance();
    }
  
    /**
     * Get the auth object
     *
     * @param void
     * 
     * @return User_Model_Row_User
     */
    public function auth()
    {
        return $this->_auth;
    }
}