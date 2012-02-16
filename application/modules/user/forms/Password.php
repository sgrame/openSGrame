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
 * User_Form_Password
 *
 * Password recovery form
 *
 * @category User
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_Form_Password extends SG_Form 
{
    /**
     * Init the form by adding all elements
     * 
     */
    public function init()
    {
        // form config
        $this->setAttrib('id', 'user-password');
        
        // old password
        $this->addElement('password', 'old_pwd', array(
            'label'      => 'Current password',
            'required'   => true,
            'maxlength'     => 128,
        ));
        
        
        // new password
        $validator = new Zend_Validate_StringLength(6, 32);
        $this->addElement('password', 'new_pwd', array(
            'label'      => 'New password',
            'required'   => true,
            'maxlength'     => 128,
            'validators' => array($validator),
        ));
        $this->addElement('password', 'new_pwd_confirm', array(
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
        
        $this->addDisplayGroup(
            array('old_pwd', 'new_pwd', 'new_pwd_confirm'), 
            'password'
        );
        $this->getDisplayGroup('password')->setLegend('Change password');
            
        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Save',
        ));
        $this->addButtonGroup(
            array('submit'),
            'submit'
        );
        
        $this->addElement('hash', 'login_csrf', array(
            'salt' => 'password-change-form'
        ));
    }

    /**
     * Custom validation
     * 
     * @param    array
     * @return    bool
     */
    public function isValid($data)
    {
        $valid = parent::isValid($data);
        
        // check first if form is complete
        if(!$valid)
        {
            return;
        }
        
        // check the current password (if it is in the form)
        $oldPwd = $this->getElement('old_pwd');
        if($oldPwd) {
            $user = Zend_Auth::getInstance()->getIdentity();
            /* @var $user User_Model_Row_User */
            if(!$user->checkPassword($oldPwd->getValue()))
            {
                $oldPwd->addError(
                    'Password not valid.'
                );
                $valid = false;
            }
        }

        // check of the new password is equal to the confirm value
        $newPwd = $this->getElement('new_pwd');
        $newPwdConfirm = $this->getElement('new_pwd_confirm');
        
        if($newPwd->getValue() !== $newPwdConfirm->getValue())
        {
            $newPwd->markAsError();
            $newPwdConfirm->addError(
                'New passwords are not equal.'
            );
            $valid = false;
        }
        
        if(!$valid)
        {
            $this->_errorsExist = true;
        }
        
        return $valid;
    }
}