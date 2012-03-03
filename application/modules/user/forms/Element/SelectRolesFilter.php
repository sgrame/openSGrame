<?php
/**
 * @category User
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * User_Form_Element_SelectRolesFilter
 *
 * Users role filter element
 *
 * @category User
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_Form_Element_SelectRolesFilter
    extends Zend_Form_Element_Select 
{
    /**
     * Init the class
     */
    public function init()
    {
        // get the possible roles
        $roles = new User_Model_Role();
        
        $translate = Zend_Registry::get('Zend_Translate');
        $options = array(
            'all'  => $translate->translate('All'),
            'none' => $translate->translate('No role')
        ) + $roles->getRolesAsArray();
        
        $this->setMultiOptions($options);
    }
}