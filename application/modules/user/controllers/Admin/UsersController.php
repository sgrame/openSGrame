<?php
/**
 * @category User_Admin
 * @package  Controller
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * User_Admin_UsersController
 *
 * Users management
 *
 * @category User_Admin
 * @package  Controller
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_Admin_UsersController extends Zend_Controller_Action
{
    /**
     * Model
     * 
     * @var User_Model_User
     */
    protected $_model;
    
    /**
     * Messenger
     * 
     * @var TB_Controller_Action_Helper_Messenger
     */
    protected $_messenger;
    
  
    /**
     * Init the controller
     */
    public function init()
    {
       $this->_model = new User_Model_User();
       $this->_messenger = $this->_helper->getHelper('Messenger');
       
       $this->view->layout()->title = $this->view->t('Manage users');
       
       // set the subnavigation
       $this->view->layout()->subnavId = 'sub-user-admin';
    }
    
    public function __dummyAction()
    {
        $prefix = 'user' . rand(1000, 9999);
        $users = new User_Model_DbTable_User();
        for($i = 0; $i < 50; $i++) {
            $user = $users->createRow(array(
                'username' => $prefix . $i,
                'email'    => $prefix . $i . '@test.be'
            ));
            $user->save();
        }
        
        $this->_goToOverview();
    }

    /**
     * Users overview
     */
    public function indexAction()
    {
        $users = $this->_model->getUsers(
            $this->_request->getParam('page', 0),
            $this->_request->getParam('order', 'created'),
            $this->_request->getParam('direction', 'desc'),
            $this->_request->getParams()
        );
        $this->view->users = $users;
    }

    /**
     * Add a new user
     */
    public function addAction()
    {
        // check access first
        $this->_checkIsUserManager();
        
        $this->view->layout()->title = $this->view->t('Add user');
        
        // get the form
        $form = $this->_model->getUserForm();
        $form->setAction($this->view->url());
        $this->view->form = $form;
        
        // Post?
        if (!$this->_request->isPost()) {
            return;
        }
        
        // Validate the form
        $isValid = $form->isValid($this->_request->getPost());
        
        // Check if cancel not clicked
        if($form->getElement('cancel')->isChecked()) {
            $this->_goToOverview();
            return;
        }
        
        // Has errors?
        if(!$isValid) {
            return;
        }
        
        // create the user in the DB
        $user = $this->_model->saveUserForm($form);
        if(!$user) {
            $this->_messenger->addError($this->view->t(
                'There was a problem saving the user data, try again or contact platform administrator.'
            ));
            return;
        }
            
        // we created the user
        $this->_messenger->addSuccess($this->view->t(
            'User <strong>%s</strong> created', $user->username
        ));
        
        $this->_goToOverview();
    }

    /**
     * Edit an existing user
     */
    public function editAction()
    {
        // check access first
        $this->_checkIsUserManager();
        
        // try to get the user
        $user = $this->_checkUserExists($this->getRequest()->getParam('id'));
                
        $this->view->layout()->title = $this->view->t(
            'Edit user <em>%s</em>', $user->username
        );
        
        // get the form
        $form = $this->_model->getUserForm($user);
        $form->setAction($this->view->url());
        $this->view->form = $form;
        
        // Post?
        if (!$this->_request->isPost()) {
            return;
        }
        
        // Validate the form
        $isValid = $form->isValid($this->_request->getPost());
        
        // Check if cancel not clicked
        if($form->getElement('cancel')->isChecked()) {
            $this->_goToOverview();
            return;
        }
        
        // Has errors?
        if(!$isValid) {
            return;
        }
        
        // update the user in the DB
        $user = $this->_model->saveUserForm($form);
        if(!$user) {
            $this->_messenger->addError($this->view->t(
                'There was a problem saving the user data, try again or contact platform administrator.'
            ));
            return;
        }
            
        // we created the user
        $this->_messenger->addSuccess($this->view->t(
            'User <strong>%s</strong> updated', $user->username
        ));
        
        $this->_goToOverview();
    }

    /**
     * Delete user
     */
    public function deleteAction()
    {
        // check access first
        $this->_checkIsUserManager();
        
        // try to get the user
        $user = $this->_checkUserExists($this->getRequest()->getParam('id'));
                
        $this->view->layout()->title = $this->view->t(
            'Delete user <em>%s</em>', $user->username
        );
        
        // get the form
        $form = $this->_model->getUserConfirmForm('delete', $user);
        $form->setAction($this->view->url());
        $this->view->form = $form;
        $this->_helper->viewRenderer->setRender('confirm'); 
        
        // Post?
        if (!$this->_request->isPost()) {
            return;
        }
        
        // Validate the form
        $isValid = $form->isValid($this->_request->getPost());
        
        // Check if cancel not clicked
        if($form->getElement('submit')->isChecked()) {
            $username = $user->username;
            $user->delete();
            $this->_messenger->addSuccess($this->view->t(
                'User <strong>%s</strong> deleted',
                $username
            ));
        }
        
        $this->_goToOverview();
    }
    
    /**
     * Activate user
     */
    public function activateAction()
    {
        // check access first
        $this->_checkIsUserManager();
        
        // try to get the user
        $user = $this->_checkUserExists($this->getRequest()->getParam('id'));
        
        $user->activate();
        $this->_messenger->addSuccess($this->view->t(
            'User <strong>%s</strong> is activated.', $user->username
        ));
        
        $this->_goToOverview();
    }
    
    /**
     * Block user
     */
    public function blockAction()
    {
        // check access first
        $this->_checkIsUserManager();
        
        // try to get the user
        $user = $this->_checkUserExists($this->getRequest()->getParam('id'));
        
        $user->block();
        $this->_messenger->addSuccess($this->view->t(
            'User <strong>%s</strong> is blocked.', $user->username
        ));
        
        $this->_goToOverview();
    }
    
    /**
     * Unlock user
     */
    public function unlockAction()
    {
        // check access first
        $this->_checkIsUserManager();
        
        // try to get the user
        $user = $this->_checkUserExists($this->getRequest()->getParam('id'));
        
        $user->unlock();
        $this->_messenger->addSuccess($this->view->t(
            'User <strong>%s</strong> is unlocked.', $user->username
        ));
        
        $this->_goToOverview();
    }
    
    
    /**
     * Check is user administrator
     */
    protected function _checkIsUserManager()
    {
        $acl = Zend_Registry::get('acl');
        if(!$acl->isUserAllowed('user:admin:users', 'administer')) {
            throw new SG_Controller_Action_NotAuthorized_Exception(
                'User is not an user administrator'
            );
        }
    }
    
    
    
    /**
     * Helper to redirect to the users overview page
     * 
     * @param void
     * 
     * @return void
     */
    protected function _goToOverview()
    {
        $this->_redirect($this->view->url(array(
            'module'     => 'user',
            'controller' => 'users',
            'action'     => 'index',
        ), 'admin'));
    }
    
    /**
     * Helper to get a user by its id, if none found => redirect to overview
     * 
     * @param $userId
     * 
     * @return User_Model_Row_User
     */
    protected function _checkUserExists($userId)
    {
        $user = $this->_model->findById($userId);
        if(!$user) {
            $this->_messenger->addWarning($this->view->t(
                'User not found'
            ));
            $this->_goToOverview();
        }
        
        return $user;
    }
}

