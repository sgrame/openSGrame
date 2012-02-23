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
        $username  = new Zend_Form_Element_Text('user_name');
        $username  ->setLabel('Username')
                   ->setRequired(true)
                   ->setAttrib('autocomplete', 'off');
                   
        // new password
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
                        'new_pwd'
                    )
                ),
            )
        ));
        
        $firstname = new Zend_Form_Element_Text('firstname');
        $firstname ->setLabel('Firstname');
         
        $lastname  = new Zend_Form_Element_Text('lastname');
        $lastname  ->setLabel('Lastname');
        
        $groups    = new User_Form_Element_SelectGroups('groups');
        $groups    ->setLabel('Group');
        
        $roles     = new User_Form_Element_MultiCheckboxRoles('roles');
        $roles     ->setLabel('Role(s)'); 
        
        $status    = new User_Form_Element_RadioStatus('status');
        $status    ->setLabel('Status');
        
        $submit    = new Zend_Form_Element_Submit('submit');
        $submit    ->setLabel('Save');


        // add elements
        $this->addElements(array(
            $username, 
            $firstname,
            $lastname,
            $groups,
            $roles,
            $status,
            $submit,
        ));
        
        // Login group
        $this->addDisplayGroup(
            array('user_name', 'user_password', 'user_password_confirm'),
            'login'
        );
        $this->getDisplayGroup('login')->setLegend('User login');
        
        // User details group
        $this->addDisplayGroup(
            array('firstname', 'lastname', 'roles', 'groups', 'status'),
            'user'
        );
        $this->getDisplayGroup('user')->setLegend('User details');
        
        
        // button group
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

