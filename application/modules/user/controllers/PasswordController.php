<?php
/**
 * @category User
 * @package  Controller
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * User_PasswordController
 *
 * Password recovery controller
 *
 * @category User
 * @package  Controller
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_PasswordController extends Zend_Controller_Action
{
    /**
     * The authentication model
     * 
     * @var User_Model_User
     */
    protected $_model;
    
    /**
     * The messenger
     * 
     * @var TB_Controller_Action_Helper_Messenger
     */
    protected $_messenger;
    
    /**
     * The namespace for the password reset session
     * 
     * @var string
     */
    const NS_PASSWORD_RESET = 'user:password-reset';
    
    
    /**
     * Initiate the controller
     */
    public function init()
    {
        $this->_messenger = $this->_helper->getHelper('Messenger');
        
        $this->_model = new User_Model_User();
    }
    
    /**
     * Change the user password
     */
    public function indexAction()
    {
        $form = new User_Form_Password();
        $this->view->form = $form;
        
        // check for password reset
        $ns   = new Zend_Session_Namespace(self::NS_PASSWORD_RESET);
        if($ns && !empty($ns->uuid)) {
            $userAction = $this->_model->getUserActionByUuid($ns->uuid);
            if($userAction) {
                $form->removeElement('old_pwd');
            }
        }
        
        if (!$this->_request->isPost()) {
            return;
        }
        
        if(!$form->isValid($this->_request->getPost())) {
            $this->_messenger->addError(
                '<strong>Check form values</strong>'
            );
            return;
        }
        
        // change the password
        $auth = new User_Model_Auth();
        $user = $auth->getAuthenticatedUser();
        $this->_model->changeUserPassword($user, $form->getValue('new_pwd'));
        
        // remove the namespace (if any)
        if($ns || isset($userAction)) {
            Zend_Session::namespaceUnset(self::NS_PASSWORD_RESET);
            $userAction->setUsed();
            $userAction->save();
        }
        
        $this->_messenger->addSuccess(
            '<strong>Password is changed</strong>'
        );
       
        $this->_redirect('/user/password');
    }

    /**
     * Reset the user password
     */
    public function resetAction()
    {
        // check not authenticated
        $auth = new User_Model_Auth();
        if($auth->hasAuthenticatedUser()) {
            $this->_redirect('/user/password');
        }
      
        // show form
        $this->_helper->layout->setLayout('layout-well');
      
        $form = new User_Form_PasswordReset();
        $form->setAction($this->view->url());
        $this->view->form = $form;
        
        if (!$this->_request->isPost()) {
            return;
        }
        
        if(!$form->isValid($this->_request->getPost())) {
            $this->_messenger->addError(
                '<strong>Fill in your username or email</strong>'
            );
            return;
        }
        
        $user = $this->_model->findByUsernameOrEmail($form->getValue('username'));
        if(!$user) {
            $this->_messenger->addError(
                '<strong>We could not find you, check username or email.</strong>'
            );
            return;
        }
        
        if(!$this->_model->resetPassword($user)) {
            $this->_messenger->addWarning(
                array(
                    '<strong>Something went wrong while sending out the password reset email.</strong>',
                    'Contact an administrator or try again later'
                )
            );
            return;
        }
        
        // password recovery mail sent
        $this->_messenger->addSuccess(
            array(
                '<strong>Password reset email send!</strong>',
                'Check your mail.'
            )
        );
    }

    /**
     * Token (pasword reset complete) action
     */
    public function actionAction()
    {
        $uuid = $this->getRequest()->getParam('uuid', false);
        if(!$uuid) {
            throw new SG_Controller_Action_NotFound_Exception('Action uuid not in request');
        }
        
        $action = $this->_model->getUserActionByUuid($uuid);
        if(!$action) {
            throw new SG_Controller_Action_NotFound_Exception('Action uuid not found');
        }
        if($action->isUsed()) {
            throw new SG_Controller_Action_NotFound_Exception(
                'The user action is already used'
            );
        }
        
        // get the associated user
        $user = $this->_model->findById($action->user_id);
        if(!$user) {
            throw new Zend_Controller_Action_Exception(
                'Could not find the user for the action'
            );
        }
        
        // Check if user may still access the platform
        if($user->isBlocked()) {
            throw new SG_Controller_Action_NotAuthorized_Exception(
                'The user (' . $user->username . ') is blocked'
            );
        }
        
        // switch between supported actions
        switch($action->action) {
            case 'password:reset':
                $ns = new Zend_Session_Namespace(self::NS_PASSWORD_RESET);
                // user has 5min to change his password
                $ns->setExpirationSeconds(5 * 60);
                $ns->uuid = $uuid;
                           
            case 'login:once':
                // authenticate the user
                $auth = new User_Model_Auth();
                $auth->authenticateUser($user);
                
                // redirect to the change password form
                $this->_redirect('/user/password');
                break;
        }
        
        throw new SG_Controller_Action_NotFound_Exception(
            'Action "' . $action->action . '" not supported'
        );       
    }
}

