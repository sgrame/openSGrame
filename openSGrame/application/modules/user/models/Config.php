<?php
/**
 * @category User
 * @package  Model
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * User_Model_Config
 *
 * User config model
 *
 * @category User
 * @package  Model
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_Model_Config
{
    /**
     * get the user config form
     * 
     * @param void
     * 
     * @return User_Form_Config
     */
    public function getForm()
    {
        $form = new User_Form_Config();
        
        $values = array(
            'minimum_password_length' => $this->getMinimumPasswordLength(),
            'maximum_login_attempts'  => $this->getMaximumLoginAttempts(),
        );
        $form->populate($values);
        
        return $form;
    }
    
    /**
     * Save a user from its User form
     * 
     * @param User_Form_User $form 
     * 
     * @return bool
     *     success
     */
    public function saveForm(User_Form_Config $form)
    {
        $this->setMinimumPasswordLength(
            $form->getValue('minimum_password_length')
        );
        $this->setMaximumLoginAttempts(
            $form->getValue('maximum_login_attempts')
        );
        
        return true;
    }
    
    
    /**
     * Get the min length for user passwords
     * 
     * @param void
     * 
     * @return int
     */
    public function getMinimumPasswordLength()
    {
        return (int)SG_Variables::getInstance()->get(
            'user_minimum_password_length', 
            6
        );
    }
    
    /**
     * Set the min length for user passwords
     * 
     * @param int $length
     * 
     * @return User_Model_Config
     */
    public function setMinimumPasswordLength($length)
    {
        SG_Variables::getInstance()->set(
            'user_minimum_password_length', 
            (int)$length
        );
        return $this;
    }
    
    
    /**
     * Get the maximum of wrong password logins before user gets locked out.
     * 
     * 0 = no limit
     * 
     * @param void
     * 
     * @return int
     */
    public function getMaximumLoginAttempts()
    {
        return (int)SG_Variables::getInstance()->get(
            'user_maximum_login_attempts', 
            0
        );
    }
    
    /**
     * Set the maximum of wrong password logins before user gets locked out.
     * 
     * 0 = no limit
     * 
     * @param int $attempts
     * 
     * @return User_Model_Config
     */
    public function setMaximumLoginAttempts($attempts)
    {
        SG_Variables::getInstance()->set(
            'user_maximum_login_attempts', 
            (int)$attempts
        );
        return $this;
    }
    
}

