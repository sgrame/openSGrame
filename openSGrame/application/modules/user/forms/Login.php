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
 * User_Form_Login
 *
 * Main platform login form
 *
 * @category User
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_Form_Login extends SG_Form
{
    /**
     * Configure user form.
     *
     * @return void
     */
    public function init()
    {
        // form config
        $this->setAttrib('id', 'user-login');

        // create elements
        $username  = new Zend_Form_Element_Text('username');
        $password  = new Zend_Form_Element_Password('password');
        $submit    = new Zend_Form_Element_Submit('submit');
        //$remember  = new Zend_Form_Element_Checkbox('remember');

        $username->setLabel('Username')
                 ->setRequired(true);

        $password->setLabel('Password')
                 ->setRequired(true);

        $submit->setLabel('Login');
        //$remember->setLabel('Remember me on this computer');

        // add elements
        $this->addElements(array(
            $username, 
            $password, 
            $submit,
            //$remember,
        ));
        
        // add display group
        $this->addDisplayGroup(
            array('username', 'password'),
            'login'
        );
        $this->getDisplayGroup('login')->setLegend('Login');
        
        $this->addButtonGroup(
            array('submit'),
            'submit'
        );
        
        $this->addElement('hash', 'login_csrf', array('salt' => 'login-form'));
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

