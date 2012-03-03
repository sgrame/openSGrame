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
 * User_Form_User
 *
 * User creation form
 *
 * @category User
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_Form_User extends SG_Form
{
    /**
     * Configure user form.
     *
     * @return void
     */
    public function init()
    {
        // form config
        $this->setAttrib('id', 'user-form');

        // create elements
        $username  = new Zend_Form_Element_Text('username');
        $username  ->setLabel('Username')
                   ->setRequired(true)
                   ->setAttrib('autocomplete', 'off');
                   
        $this->addElement('email', 'email', array(
            'label'      => 'Email',
            'required'   => true,
            'attribs'    => array(
                'autocomplete' => 'off',
            ),
        ));
                   
        // password
        $validator = new Zend_Validate_StringLength(6, 32);
        $this->addElement('password', 'user_password', array(
            'label'      => 'Password',
            'required'   => true,
            'maxlength'     => 128,
            'validators' => array($validator),
        ));
        $this->addElement('password', 'user_password_confirm', array(
            'label'      => 'Repeat password',
            'required'   => true,
            'maxlength'     => 128,
            'description'=> 'Password must contain at least 6 characters.',
            'validators' => array(
                array(
                    'validator' => 'FormIdentical',
                    'options'    => array(
                        'user_password'
                    )
                ),
            )
        ));
        
        $groups    = new User_Form_Element_SelectGroups('groups');
        $groups    ->setLabel('Group');
        
        $roles     = new User_Form_Element_MultiCheckboxRoles('roles');
        $roles     ->setLabel('Role(s)'); 
        
        $status    = new User_Form_Element_RadioStatus('status');
        $status    ->setLabel('Status');
        $status    ->setValue(1);
        
        $submit    = new Zend_Form_Element_Submit('submit');
        $submit    ->setLabel('Save');
        
        $cancel    = new Zend_Form_Element_Submit('cancel');
        $cancel    ->setLabel('Cancel');


        // add elements
        $this->addElements(array(
            $username, 
            //$firstname,
            //$lastname,
            $groups,
            $roles,
            $status,
            $submit,
            $cancel,
        ));
        
        // Login group
        $this->addDisplayGroup(
            array('username', 'email', 'user_password', 'user_password_confirm'),
            'login'
        );
        $this->getDisplayGroup('login')->setLegend('User login');
        
        // User details group
        $this->addDisplayGroup(
            array(
                //'firstname', 
                //'lastname', 
                'roles', 
                'groups', 
                'status'
            ),
            'user'
        );
        $this->getDisplayGroup('user')->setLegend('User details');
        
        
        // button group
        $this->addButtonGroup(
            array('submit', 'cancel'),
            'submit'
        );
        
        $this->addElement('hash', 'login_csrf', array('salt' => 'user-form'));
        $this->addElement('hidden', 'user_id');
    }

    /**
     * Validate the form
     *
     * @param  array $data
     * @return boolean
     */
    public function isValid($data)
    {
        $isValid = parent::isValid($data);
        
        if(!$isValid) {
            return;
        }
        
        // user id
        $userId = $this->getValue('user_id');
        
        $model = new User_Model_DbTable_User();
        
        // check username exists
        if($model->usernameExists($this->getValue('username'), $userId)) {
            $isValid = false;
            $this->getElement('username')->addError(
                'Username already in use for other user'
            );
            $this->markAsError();
        }
        
        // check email exists
        $model = new User_Model_DbTable_User();
        if($model->emailExists($this->getValue('email'), $userId)) {
            $isValid = false;
            $this->getElement('email')->addError(
                'Email already in use for other user'
            );
            $this->markAsError();
        }
        
        if(!$isValid) {
            $this->buildErrorDecorators();
        }
        
        return $isValid;
    }
}

