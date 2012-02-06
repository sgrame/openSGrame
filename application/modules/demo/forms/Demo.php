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

        // standard elements --------------------------------------------------
        $mail        = new Zend_Form_Element_Text('email');
        $name        = new Zend_Form_Element_Text('name');
        $checkbox    = new Zend_Form_Element_Checkbox('checkbox');
        $password    = new Zend_Form_Element_Password('password');
                
        $mail->setLabel('Mail')
            ->setRequired(true)
            ->addValidator('emailAddress')
            ->setDescription('Add your email address.');

        $name->setLabel('Name')
            ->setRequired(true)
            ->setDescription('Add your full name.');
            
        $password->setLabel('Password')
            ->setRequired(true)
            ->setDescription('Enter your secret password.');
            
        $checkbox->setLabel('Checkbox')
            ->setRequired(true)
            ->setDescription('Test checkbox description.');
            
        $this->addElements(array(
            $mail, 
            $name,
            $password,
            $checkbox,
        ));
            
        $this->addDisplayGroup(
            array('name', 'email', 'password', 'checkbox'),
            'standard'
        );
        $this->getDisplayGroup('standard')->setLegend('Standard elements');
        
        
        // multi elements ----------------------------------------------------
        $radio          = new Zend_Form_Element_Radio('radio');
        $multiCheckbox  = new Zend_Form_Element_MultiCheckbox('multiCheckbox');
        $select         = new Zend_Form_Element_Select('select');
        $multiSelect    = new Zend_Form_Element_Multiselect('multiSelect');
        
        $radio->setLabel('Radio')
            ->setMultiOptions(array(
                '1' => 'test1',
                '2' => 'test2'
            ))
            ->setRequired(true)
            ->setDescription('Select the test you prefer.');
        $radioInline = clone $radio;
        $radioInline->setName('radioInline')
            ->setAttrib('label_class', 'inline');

        $multiOptions = array(
            'view'    => 'view',
            'edit'    => 'edit',
            'comment' => 'comment'
        );
        $multiCheckbox->setLabel('Multi Checkbox')
            ->addValidator('Alpha')
            ->setMultiOptions($multiOptions)
            ->setRequired(true)
            ->setDescription('Check the rights');
        $multiInline = clone $multiCheckbox;
        $multiInline->setName('multiInline')
            ->setAttrib('label_class', 'inline');

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
            
        $multiSelectOptions = array(
            'one'   => 'Select One',
            'two'   => 'Select Two',
            'three' => 'Select Three',
        );
        $multiSelect->setLabel('Multi Select')
            ->setMultiOptions($multiSelectOptions)
            ->setRequired(true)
            ->setDescription('Select one or more');
            
        $this->addElements(array(
            $radio,
            $radioInline,
            $multiCheckbox,
            $multiInline,
            $select,
            $multiSelect,
        ));
        
        
        $this->addDisplayGroup(
            array(
                'radio', 'radioInline', 
                'multiCheckbox', 'multiInline',
                'select', 'multiSelect'
            ),
            'multielements'
        );
        $this->getDisplayGroup('multielements')->setLegend('Multi elements');
        
        
        // textarea
        $textarea = new Zend_Form_Element_Textarea('textarea');
        $textarea->setLabel('Textarea')
            ->setRequired(true)
            ->setDescription('Some description for the textarea')
            ->setAttrib('class', 'input-xlarge')
            ->setAttrib('rows', 3);
        $this->addElements(array(
            $textarea,
        ));
        
        $this->addDisplayGroup(
            array('textarea'),
            'textbox'
        );
        $this->getDisplayGroup('textbox')->setLegend('Text area');
        
        
        // files
        $file = new Zend_Form_Element_File('file');
        $file->setLabel('File')
            ->setRequired(true)
            ->setDescription('Add your file');
        $this->addElements(array(
            $file,
        ));
        $this->setAttrib('enctype', 'multipart/form-data');
        
        $this->addDisplayGroup(
            array('file'),
            'files'
        );
        $this->getDisplayGroup('files')->setLegend('Manage files');
        
        
        // captcha
        $captcha     = new Zend_Form_Element_Captcha('captcha', array('captcha' => 'Figlet'));
        $captcha->setLabel('Captcha:')
            ->setRequired(true)
            ->setDescription("This is a test");
            
        $this->addElements(array(
            $captcha,
        ));
        
        $this->addDisplayGroup(
            array('captcha'),
            'captchaElement'
        );
        $this->getDisplayGroup('captchaElement')->setLegend('Captcha');
        
        
        // hidden
        $userId      = new Zend_Form_Element_Hidden('id');
        $userId->addValidator('digits');
        
        $this->addElements(array(
            $userId, 
        ));
        
        
        // buttons
        $submit      = new Zend_Form_Element_Submit('submit');
        $cancel      = new Zend_Form_Element_Reset('cancel');
        $test        = new Zend_Form_Element_Button('test');
        
        $submit->setLabel('Save');
        $cancel->setLabel('Cancel');
        $test->setLabel('Test');
        
        $this->addElements(array(
            $submit, 
            $cancel,
            $test,
        ));
        
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

