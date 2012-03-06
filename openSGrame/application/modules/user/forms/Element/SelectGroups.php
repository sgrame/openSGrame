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
 * User_Form_Element_SelectGroups
 *
 * Dropdown selector for groups
 *
 * @category User
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_Form_Element_SelectGroups extends Zend_Form_Element_Select 
{
  	/**
  	 * Init the class
  	 */
  	public function init()
  	{
    		// get the possible roles
        $groups = new User_Model_Group();
        
        $translate = Zend_Registry::get('Zend_Translate');

        $options = $groups->getGroupsAsArray(false);
        $options = array(null => $translate->translate('-- Choose --')) 
                   + $groups->getGroupsAsArray(false);
        
        $this->setMultiOptions($options);
  	}
}