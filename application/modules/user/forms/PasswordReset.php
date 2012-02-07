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
 * User_Form_PasswordReset
 *
 * Password recovery form
 *
 * @category User
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_Form_PasswordReset extends SG_Form
{
    /**
     * Configure user form.
     *
     * @return void
     */
    public function init()
    {
        // form config
        $this->setAttrib('id', 'user-password-reset');

        // create elements
        $username  = new Zend_Form_Element_Text('username');
        $submit    = new Zend_Form_Element_Submit('submit');

        $username->setLabel('Username or Email')
                 ->setRequired(true);

        $submit->setLabel('Reset password');

        // add elements
        $this->addElements(array(
            $username, 
            $submit,
        ));
        
        // add display group
        $this->addDisplayGroup(
            array('username'),
            'reset'
        );
        $this->getDisplayGroup('reset')->setLegend('Reset password');
        
        $this->addButtonGroup(
            array('submit'),
            'submit'
        );
        
        $this->addElement('hash', 'login_csrf', array('salt' => 'password-reset-form'));
    }

    /**
     * Validate the form
     *
     * @param  array $data
     * @return boolean
     */
    public function isValid($data)
    {
        if (!is_array($data)) {
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception(__METHOD__ . ' expects an array');
        }
        
        return parent::isValid($data);
    }
}

