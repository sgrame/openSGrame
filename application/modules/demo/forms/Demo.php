<?php

/**
 * Form to test out the Twitter Bootstrap integration
 */
class Demo_Form_Demo extends SG_Form
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
        $userId      = new Zend_Form_Element_Hidden('id');
        $mail        = new Zend_Form_Element_Text('email');
        $name        = new Zend_Form_Element_Text('name');
        $radio       = new Zend_Form_Element_Radio('radio');
        $multi       = new Zend_Form_Element_MultiCheckbox('multi');
        $select      = new Zend_Form_Element_Select('select');
        $captcha     = new Zend_Form_Element_Captcha('captcha', array('captcha' => 'Figlet'));
        
        
        $submit      = new Zend_Form_Element_Button('submit');
        $cancel      = new Zend_Form_Element_Reset('cancel');
        $test        = new Zend_Form_Element_Button('test');

        // config elements
        $userId->addValidator('digits');

        $mail->setLabel('Mail')
            ->setRequired(true)
            ->addValidator('emailAddress')
            ->setDescription('Add your email address.');

        $name->setLabel('Name')
            ->setRequired(true)
            ->setDescription('Add your full name.');

        $radio->setLabel('Radio')
            ->setMultiOptions(array(
                '1' => PHP_EOL . 'test1',
                '2' => PHP_EOL . 'test2'
            ))
            ->setRequired(true)
            ->setDescription('Select the test you prefer.');

        $multiOptions = array(
            'view'    => PHP_EOL . 'view',
            'edit'    => PHP_EOL . 'edit',
            'comment' => PHP_EOL . 'comment'
        );
        $multi->setLabel('Multi')
            ->addValidator('Alpha')
            ->setMultiOptions($multiOptions)
            ->setRequired(true)
            ->setDescription('Check the rights');

        $selectOptions = array(
            null => '-- Select --',
            'one'   => 'Select One',
            'two'   => 'Select Two',
            'three' => 'Select Three',
        );
        $select->setLabel('Select')
            ->setMultiOptions($selectOptions)
            ->setRequired(true)
            ->setDescription('Select the role');

        $captcha->setLabel('Captcha:')
            ->setRequired(true)
            ->setDescription("Das ist ein Test");

        $submit->setLabel('Save');
        $cancel->setLabel('Cancel');
        $test->setLabel('Test');

        // add elements
        $this->addElements(array(
            $userId, 
            $mail, 
            $name, 
            $radio, 
            $multi, 
            $select,
            $captcha, 
            $submit, 
            $cancel,
            $test,
        ));

        // add display group
        $this->addDisplayGroup(
            array('email', 'name', 'radio', 'multi', 'select', 'captcha'),
            'users'
        );
        $this->getDisplayGroup('users')->setLegend('Add User');
        
        $this->addButtonGroup(
            array('submit', 'cancel', 'test'),
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
        if ($data['name'] == 'xyz') {
            $this->getElement('name')->addError('Wrong name provided!');
        }
        return parent::isValid($data);
    }
}

