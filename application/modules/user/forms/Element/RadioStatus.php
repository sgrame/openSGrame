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
class User_Form_Element_RadioStatus extends Zend_Form_Element_Radio 
{
	/**
	 * Init the class
	 */
	public function init()
	{
	  $translate = Zend_Registry::get('Zend_Translate');
    
		// Add the possible statusses
		$options = array(
        0 => $translate->translate('Blocked'),
        1 => $translate->translate('Active'),
    );
		$this->addMultiOptions($options);
	}
}
