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
 * User_Model_User
 *
 * User model
 *
 * @category User
 * @package  Model
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_Model_User
{
    /**
     * Action name for resetting the password
     * 
     * @var string
     */
    const USER_ACTION_PASSWORD_RESET = 'password:reset';
  
    /**
     * Mapper
     * 
     * @var User_Model_DbTable_User
     */
    protected $_mapper;
    
    /**
     * Constructor
     * 
     * @param User_Model_DbTable_User $mapper optional mapper
     */
    public function __construct($mapper = null)
    {
        if(!is_null($mapper)) {
            $this->_mapper = $mapper;
        }
        else {
            $this->_mapper = new User_Model_DbTable_User();
        }
    }
  
  
    /**
     * Find by ID
     * 
     * @param int $userId
     * 
     * @return User_Model_Row_User
     */
    public function findById($userId)
    {
        $result = $this->_mapper->find($userId);
        return $result->current();
    } 
  
    /**
     * Find user by its username or email address
     * 
     * @param string $usernameOrEmail
     * 
     * @return User_Model_Row_User
     */
    public function findByUsernameOrEmail($usernameOrEmail)
    {
        // search by email
        $validator = new Zend_Validate_EmailAddress();
        if($validator->isValid($usernameOrEmail)) {
            $result = $this->_mapper->findByEmail($usernameOrEmail);
            if($result->count()) {
                return $result->current();
            }
        }
        
        // search by username
        $result = $this->_mapper->findByUsername($usernameOrEmail);
        if($result) {
            return $result->current();
        }
        
        return false;
    }
  
    /**
     * Recover password by an user row object
     * 
     * @param User_Model_Row_User $user
     * 
     * @return bool success
     */
    public function resetPassword(User_Model_Row_User $user)
    {
        $dateExpire = new Zend_Date();
            $dateExpire->add(24, Zend_Date::HOUR);
      
        // create the action record ------------------------------------
        $actionMapper = new User_Model_DbTable_UserAction();
        $action = $actionMapper->fetchNew();
            $action->user_id = $user->id;
            $action->action  = self::USER_ACTION_PASSWORD_RESET;
            $action->setDateExpire($dateExpire);
        $action->save();
        
        // send out the mail -------------------------------------------
        $templates = new Mail_Model_DbTable_Template();
        $template = $templates->findByRealm('user:password_recovery')->current();
        /* @var $template Mail_Model_Row_Template */
        
        // get the full URL
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $siteUrl = $request->getScheme() . '://' . $request->getHttpHost();
        
        // !TODO: Add variables sitename
        $args = array(
            'site:name'          => '!TODO: ADD SITENAME',
            'user:name'          => $user->username,
            'url:one-time-login' => $siteUrl . '/user/reset/action/uuid/' . $action->uuid,
            'url:login'          => $siteUrl . '/user/login',
        );
        
        $mail = new Zend_Mail();
        $mail->addTo($user->email, $user->username);
        $mail->setSubject($template->getSubjectFilled($args));
        $mail->setBodyText($template->getBodyFilled($args));
        $mail->send();
        
        return true;
    }

    /**
     * Change a users password by the given value
     * 
     * @param User_Model_Row_User
     * @param string
     *     The new password
     * 
     * @return bool
     *     Success
     */
    public function changeUserPassword(User_Model_Row_User $user, $password)
    {
        $user = $this->findById($user->id);
        $user->password = $password;
        return $user->save();
    }

    /**
     * Get an action for a user by its uuid
     * 
     * @param string
     *     The action UUID
     * 
     * @return User_Model_Row_UserAction
     */
    public function getUserActionByUuid($uuid)
    {
        $actions = new User_Model_DbTable_UserAction();
        $action = $actions->findByUuid($uuid)->current();
        
        return $action;
    }
}

