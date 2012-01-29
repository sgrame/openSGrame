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
        //$captcha     = new Zend_Form_Element_Captcha('captcha', array('captcha' => 'Figlet'));
        $submit      = new Zend_Form_Element_Button('submit');
        $cancel      = new Zend_Form_Element_Button('cancel');

        // config elements
        $userId->addValidator('digits');

        $mail->setLabel('Mail:')
            ->setRequired(true)
            ->addValidator('emailAddress');

        $name->setLabel('Name:')
            ->setRequired(true);

        $radio->setLabel('Radio:')
            ->setMultiOptions(array(
                '1' => PHP_EOL . 'test1',
                '2' => PHP_EOL . 'test2'
            ))
            ->setRequired(true);

        $multiOptions = array(
            'view'    => PHP_EOL . 'view',
            'edit'    => PHP_EOL . 'edit',
            'comment' => PHP_EOL . 'comment'
        );
        $multi->setLabel('Multi:')
            ->addValidator('Alpha')
            ->setMultiOptions($multiOptions)
            ->setRequired(true);

        /*$captcha->setLabel('Captcha:')
            ->setRequired(true)
            ->setDescription("Das ist ein Test");*/

        $submit->setLabel('Save');
        $cancel->setLabel('Cancel');

        // add elements
        $this->addElements(array(
            $userId, $mail, $name, $radio, $multi, $submit, $cancel
        ));

        // add display group
        $this->addDisplayGroup(
            array('email', 'name', 'radio', 'multi', 'captcha', 'submit', 'cancel'),
            'users'
        );
        $this->getDisplayGroup('users')->setLegend('Add User');

        // set decorators
        EasyBib_Form_Decorator::setFormDecorator($this, EasyBib_Form_Decorator::BOOTSTRAP, 'submit', 'cancel');

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

