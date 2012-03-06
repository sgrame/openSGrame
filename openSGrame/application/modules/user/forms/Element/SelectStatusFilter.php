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
 * User_Form_Element_RadioStatus
 *
 * Radio buttons to set the status
 *
 * @category User
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_Form_Element_SelectStatusFilter extends Zend_Form_Element_Select 
{
	/**
	 * Init the class
	 */
	public function init()
	{
	  $translate = Zend_Registry::get('Zend_Translate');
    
		// Add the possible statusses
		$options = array(
		    'all'     => $translate->translate('All'),
		    'active'  => $translate->translate('Active'),
        'blocked' => $translate->translate('Blocked'),
        'locked'  => $translate->translate('Locked'),
    );
		$this->addMultiOptions($options);
	}
}
