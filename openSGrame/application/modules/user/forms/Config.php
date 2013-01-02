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
class User_Form_Config extends SG_Form
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
                
        // password length
        $minPasswordRange = range(4, 32);
        $minPasswordLength = new Zend_Form_Element_Select('minimum_password_length');
        $minPasswordLength 
            ->setLabel('Min. password length')
            ->setDescription(
                'The longer the required length, the harder to force-break passwords.'
            )
            ->addMultiOptions($minPasswordRange);
        
        // number of login attempts
        $maxLoginRange     = array_merge(
            array(0 => 'Unlimitted'), 
            range(1, 15)
        );
        $maxLoginAttempts  = new Zend_Form_Element_Select('maximum_login_attempts');
        $maxLoginAttempts  
            ->setLabel('Max. login attempts')
            ->setDescription(
                'Users will be locked out if they entered several wrong passwords. This will block forced log-in attempts.'
             )
            ->addMultiOptions($maxLoginRange);
        
        // buttons
        $submit    = new Zend_Form_Element_Submit('submit');
        $submit    ->setLabel('Save');
        

        // add elements
        $this->addElements(array(
            $minPasswordLength, 
            $maxLoginAttempts,
            $submit,
        ));
        
        // Login group
        $this->addDisplayGroup(
            array(
                'minimum_password_length', 
                'maximum_login_attempts', 
            ),
            'login'
        );
        $this->getDisplayGroup('login')->setLegend('Login settings');
        
        // button group
        $this->addButtonGroup(
            array('submit'),
            'submit'
        );
        
        $this->addHash();
    }
}

