<?php
/**
 * @category Examples
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * Examples_Form_Bootstrap
 *
 * Examples of a Bootstrap Form
 *
 * @category Examples
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class Examples_Form_Bootstrap extends SG_Form
{
    /**
     * Configure user form.
     *
     * @return void
     */
    public function init()
    {
        // form config
        $this->setAttrib('id', __CLASS__);

        // standard elements --------------------------------------------------
        $mail        = new Zend_Form_Element_Text('email');
        $name        = new Zend_Form_Element_Text('name');
        $note        = new SG_Form_Element_Note('note');
        $checkbox    = new Zend_Form_Element_Checkbox('checkbox');
        $password    = new Zend_Form_Element_Password('password');
                
        $mail->setLabel('Mail')
            ->setRequired(true)
            ->addValidator('emailAddress')
            ->setDescription('Add your email address.');

        $name->setLabel('Name')
            ->setRequired(true)
            ->setDescription('Add your full name.');
            
        $note->setLabel('Note')
            ->setValue('Note text displayed here')
            ->setDescription('Simple note field');
            
        $password->setLabel('Password')
            ->setRequired(true)
            ->setDescription('Enter your secret password.');
            
        $checkbox->setLabel('Checkbox')
            ->setRequired(true)
            ->setDescription('Test checkbox description.');
            
        $this->addElements(array(
            $mail, 
            $name,
            $note,
            $password,
            $checkbox,
        ));
            
        $this->addDisplayGroup(
            array('name', 'email', 'note', 'password', 'checkbox'),
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
            ->setLabel('Radio inline')
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
            ->setLabel('Multi Checkbox inline')
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
        $again       = new Zend_Form_Element_Submit('again');
        
        $submit->setLabel('Save');
        $cancel->setLabel('Cancel');
        $test->setLabel('Test');
        $again->setLabel('Try again');
        
        $this->addElements(array(
            $submit, 
            $cancel,
            $test,
            $again,
        ));
        
        // Group the form buttons in an action fieldset
        $this->addButtonGroup(
            array('submit', 'cancel', 'test', 'again'),
            'submit'
        );
    }
}

