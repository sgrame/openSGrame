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
        if(preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])*(\.([a-z0-9])([-a-z0-9_-])([a-z0-9])+)*$/i', $usernameOrEmail)) {
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
      
        // create the action record
        $actionMapper = new User_Model_DbTable_UserAction();
        $action = $actionMapper->fetchNew();
            $action->user_id = $user->id;
            $action->action  = self::USER_ACTION_PASSWORD_RESET;
            $action->setDateExpire($dateExpire);
        $action->save();
        
        // send out the mail
        
        
    }
}

