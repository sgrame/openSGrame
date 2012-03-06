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
 * User_Form_Element_MultiCheckboxRoles
 *
 * Users role(s) multicheckbox element
 *
 * @category User
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_Form_Element_MultiCheckboxRoles
    extends Zend_Form_Element_MultiCheckbox 
{
    /**
     * Init the class
     */
    public function init()
    {
        // get the possible roles
        $roles = new User_Model_Role();
        $this->setMultiOptions($roles->getRolesAsArray());
    }
}