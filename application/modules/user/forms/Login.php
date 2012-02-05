<?php

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
        $this->setAttrib('id', 'demo_form_demo');

        // create elements
        $username       = new Zend_Form_Element_Text('username');
        $password       = new Zend_Form_Element_Password('password');
        $submit         = new Zend_Form_Element_Button('submit');
        $forgotPassword = new Zend_Form_Element_Button('forgotPassword');

        $username->setLabel('Username:')
                 ->setRequired(true);

        $password->setLabel('Password:')
                 ->setRequired(true);

        $submit->setLabel('Login');
        $forgotPassword->setLabel('Forgot your password?');

        // add elements
        $this->addElements(array(
            $username, 
            $password, 
            $submit,
            $forgotPassword,
        ));

        // add display group
        $this->addDisplayGroup(
            array('username', 'password'),
            'login'
        );
        $this->getDisplayGroup('login')->setLegend('Login');
        
        $this->addButtonGroup(
            array('submit', 'forgotPassword'),
            'submit'
        );
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

