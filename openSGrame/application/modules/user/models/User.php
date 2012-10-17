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
        if (!is_null($mapper)) {
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
        if ($validator->isValid($usernameOrEmail)) {
            $result = $this->_mapper->findByEmail($usernameOrEmail);
            if ($result->count()) {
                return $result->current();
            }
        }
        
        // search by username
        $result = $this->_mapper->findByUsername($usernameOrEmail);
        if ($result) {
            return $result->current();
        }
        
        return false;
    }
    
    
    /**
     * User form
     * 
     * @param mixed
     *     Null or User_Model_Row_User
     * 
     * @return User_Form_User
     */
    public function getUserForm($user = null)
    {
        $form = new User_Form_User();
        if ($user instanceof User_Model_Row_User) {
            $data = array(
                'username'  => $user->username,
                'firstname' => $user->firstname,
                'lastname'  => $user->lastname,
                'email'     => $user->email,
                'roles'     => array(),
                'groups'    => NULL,
                'status'    => (int)$user->isActive(),
                'user_id'   => $user->id,
            );
            
            $roles = $user->getRoles();
            foreach ($roles AS $role) {
                $data['roles'][] = $role->id;
            }
            
            $groups = $user->getGroups();
            foreach ($groups AS $group) {
                $data['groups'] = $group->id;
                // @TODO: Make the platform support multiple groups (configuration)!
                break;
            }
            
            $form->populate($data);
            $form->getElement('user_password')->setRequired(false);
            $form->getElement('user_password_confirm')->setRequired(false);
        }
        
        return $form;
    }
    
    /**
     * Save a user from its User form
     * 
     * @param User_Form_User $form 
     * 
     * @return User_Model_Row_user
     */
    public function saveUserForm(User_Form_User $form)
    {
        return $this->saveUserArray($form->getValues());
    }
    
    /**
     * Save user from array data
     * 
     * @param array
     * 
     * @return User_Model_User 
     */
    public function saveUserArray($values)
    {
        $db = $this->_mapper->getAdapter();
        $db->beginTransaction();
      
        try {
            $user = $this->_mapper->createRow();
            
            // update?
            if (!empty($values['user_id'])) {
                $user = $this->findById($values['user_id']);
                if (!$user) {
                    return false;
                }
            }
            
            // User -----------------------------------------------------------
            $user->username  = $values['username'];
            $user->email     = $values['email'];
            $user->firstname = $values['firstname'];
            $user->lastname  = $values['lastname'];
            if (!empty($values['user_password'])) {
                $user->password = $values['user_password'];
            }
            $user->blocked = ((int)$values['status'] === 1)
                ? 0
                : 1;
            $user->save();
            
            // Groups ---------------------------------------------------------
            if (empty($values['groups'])) {
                $values['groups'] = array();
            }
            if (!is_array($values['groups'])) {
                $values['groups'] = array($values['groups']);
            }
            
            // get existing user groups
            $userGroups = new User_Model_DbTable_UserGroups();
            $currentGroups = $user->getGroups();
            foreach ($currentGroups AS $group) {
                if (in_array($group->id, $values['groups'])) {
                    $arrayKey = array_search($group->id, $values['groups']);
                    unset($values['groups'][$arrayKey]);
                    continue;
                }
                
                $userGroups->deleteByGroup($group);
            }
            foreach ($values['groups'] AS $groupId) {
                $userGroups->createByUserAndGroup($user, $groupId);
            }
            
            // Roles ----------------------------------------------------------
            if (empty($values['roles'])) {
                $values['roles'] = array();
            }
            if (!is_array($values['roles'])) {
                $values['roles'] = array($values['roles']);
            }
            
            // get existing user roles
            $userRoles = new User_Model_DbTable_UserRoles();
            $currentRoles = $user->getRoles();
            foreach ($currentRoles AS $role) {
                if (in_array($role->id, $values['roles'])) {
                    $arrayKey = array_search($role->id, $values['roles']);
                    unset($values['roles'][$arrayKey]);
                    continue;
                }
                
                $userRoles->deleteByRole($role);
            }
            foreach ($values['roles'] AS $roleId) {
                $userRoles->createByUserAndRole($user, $roleId);
            }
            
            $db->commit();
            return $user;
        }
        catch (Exception $e) {
            $db->rollBack();
            SG_Log::log($e->getMessage(), SG_Log::CRIT);
        }
        
        return false;
    }

    /**
     * Get the user search form
     * 
     * @param array $search
     *     Search params
     * 
     * @return User_Form_UserSearch
     */
    public function getUserSearchForm($search = array())
    {
        $form = new User_Form_UserSearch();
        
        // @TODO: limit the groups if user can only search in own group!
        
        if (!empty($search)) {
            $form->populate($search);
        }
        
        return $form;
    }
    
    /**
     * User action confirm form proxy
     * 
     * @param string $action
     *     Action to perform
     * @param User_Model_Row_User $user
     * 
     * @return User_Form_Confirm
     */
    public function getUserConfirmForm($action, User_Model_Row_User $user)
    {
        $form = new User_Form_Confirm();
        $form->getElement('id')->setValue((int)$user->id);
        
        $translator = SG_Translator::getInstance();
        
        $legendText = null;
        $noteText   = null;
        $buttonText = null;
        
        switch ($action) {
          case 'delete':
              $legendText = $translator->t(
                  'Delete user'
              );
              $noteText = $translator->t(
                  'Are you sure that you want to delete user <strong>%s</strong>?',
                  $user->username
              );
              break;
        }
        
        if ($legendText) {
            $form->getDisplayGroup('confirm')->setLegend($legendText);
        }
        if ($noteText) {
            $form->getElement('note')->setValue($noteText);
        }
        if ($buttonText) {
            $form->getElement('submit')->setLabel($buttonText);
        }
        
        return $form;
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

        $vars = SG_Variables::getInstance();        
        
        // !TODO: Add variables sitename
        $args = array(
            'site:name'          => $vars->get('site_name'),
            'user:name'          => $user->username,
            'url:one-time-login' => $siteUrl . '/user/reset/action/uuid/' . $action->uuid,
            'url:login'          => $siteUrl . '/user/login',
            'url:site'           => $siteUrl,
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
        $user->save();
        
        $auth = Zend_Auth::getInstance();
        $auth->getStorage()->write($user);
        
        return $user;
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
    
    
    /**
     * Get (all) the users
     * 
     * @param bool $paged
     *     Should the result be an paged result set
     * @param string $order
     *     Order by
     * @param array $search
     *     Array of search params
     * 
     * @return Zend_Paginator
     */
    public function getUsers(
        $page = 0, $order = 'created', $direction = 'desc', $search = array()
    )
    {
        // limit the list by ACL
        $acl = Zend_Registry::get('acl');
        if(!$acl->isUserAllowed('user:admin', 'administer users of the system')) {
            $search['excludeSystemUsers'] = true;
        }
        
        // administer only group users
        if (!$acl->isUserAllowed('user:admin', 'administer all users')) {
            $auth = new User_Model_Auth();
            $user = $auth->getAuthenticatedUser();
            
            if (!$user) {
                $search['groups'] = array('NONE');
            }
            else {
                $groups = $user->getGroups();
                $groupIds = array();
                foreach($groups AS $group) {
                    $groupIds[] = $group->id;
                }
                $search['groups'] = $groupIds;
            }
        }
        
        $users = $this->_mapper->fetchBySearch($search, $order, $direction);
        $paged = Zend_Paginator::factory($users);
        $paged->setCurrentPageNumber((int)$page);
        return $paged;
    }
    
    /**
     * Count the number of users by the Group
     * 
     * @param mixed $group
     *     Group object or Group ID
     * 
     * @return int 
     */
    public function countByGroup($group)
    {
        $groupId = User_Model_Group::extractGroupId($group);
        return $this->_mapper->countByGroupId($groupId);
    }
    
    
    /**
     * Get the user id from the user ID or user object
     * 
     * @param mixed $user
     *     The user id or user object
     * 
     * @return int
     */
    public static function extractUserId($user)
    {
        if (is_numeric($user)) {
            return (int)$user;
        }
        
        if ($user instanceof User_Model_Row_User) {
            return (int)$user->id;
        }
        
        throw new Zend_Db_Table_Row_Exception(
            'No valid user ID or user object'
        );
    }
    
    /**
     * Load the user or return the given User object
     * 
     * @param int|User_Model_Row_User
     * 
     * @return User_Model_Row_User
     */
    public static function load($user)
    {
        if ($user instanceof User_Model_Row_User) {
            return $user;
        }
        
        $users = new User_Model_User();
        return $users->findById($user);
    }
}

