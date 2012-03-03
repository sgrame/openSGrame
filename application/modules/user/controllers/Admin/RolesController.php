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
 * User_Admin_RolesController
 *
 * Users management
 *
 * @category User_Admin
 * @package  Controller
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_Admin_RolesController extends SG_Controller_Action
{
    /**
     * ACL
     */
    protected $_aclResource  = 'user:admin';
    protected $_aclPrivilege = 'administer roles';
    
    /**
     * Model
     * 
     * @var User_Model_Role
     */
    protected $_model;
    
    /**
     * Messenger
     * 
     * @var TB_Controller_Action_Helper_Messenger
     */
    protected $_messenger;
  
    /**
     * Init this controller
     */
    public function init()
    {
       $this->_model = new User_Model_Role();
       $this->_messenger = $this->_helper->getHelper('Messenger');
       
       $this->view->layout()->title = $this->view->t('Manage roles');
       
       // set the subnavigation
       $this->view->layout()->subnavId = 'sub-user-admin';
    }

    /**
     * 
     */
    public function indexAction()
    {
        $roles = $this->_model->getRoles(
            $this->_request->getParam('page', 0),
            $this->_request->getParam('order', 'name'),
            $this->_request->getParam('direction', 'asc'),
            $this->_request->getParams()
        );
        $this->view->roles = $roles;
    }
    
    /**
     * Add a new role
     */
    public function addAction()
    {
        $this->view->layout()->title = $this->view->t('Add role');
        
        $form = $this->_model->getRoleForm();
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
        
        // create the role in the DB
        $role = $this->_model->saveRoleForm($form);
        if(!$role) {
            $this->_messenger->addError($this->view->t(
                'There was a problem saving the role, try again or contact platform administrator.'
            ));
            return;
        }
            
        // we created the role
        $this->_messenger->addSuccess($this->view->t(
            'Role <strong>%s</strong> created', $role->name
        ));
        
        $this->_goToOverview();
    }

    /**
     * Edit an existing role
     */
    public function editAction()
    {
        // try to get the role
        $role = $this->_checkRoleExists($this->getRequest()->getParam('id'));
                
        $this->view->layout()->title = $this->view->t(
            'Edit role <em>%s</em>', $role->name
        );
        
        // get the form
        $form = $this->_model->getRoleForm($role);
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
        
        // update the role in the DB
        $role = $this->_model->saveRoleForm($form);
        if(!$role) {
            $this->_messenger->addError($this->view->t(
                'There was a problem saving the role, try again or contact platform administrator.'
            ));
            return;
        }
            
        // we created the role
        $this->_messenger->addSuccess($this->view->t(
            'Role <strong>%s</strong> updated', $role->name
        ));
        
        $this->_goToOverview();
    }

    /**
     * Delete group
     */
    public function deleteAction()
    {
        // try to get the role
        $role = $this->_checkRoleExists($this->getRequest()->getParam('id'));
                
        $this->view->layout()->title = $this->view->t(
            'Delete role <em>%s</em>', $role->name
        );
        
        // get the form
        $form = $this->_model->getRoleConfirmForm('delete', $role);
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
            $name = $role->name;
            $role->delete();
            $this->_messenger->addSuccess($this->view->t(
                'Role <strong>%s</strong> deleted',
                $name
            ));
        }
        
        $this->_goToOverview();
    }
    
    

    /**
     * Helper to redirect to the roles overview page
     * 
     * @param void
     * 
     * @return void
     */
    protected function _goToOverview()
    {
        $this->_redirect($this->view->url(array(
            'module'     => 'user',
            'controller' => 'roles',
            'action'     => 'index',
        ), 'admin', true));
    }
    
    /**
     * Helper to get a role by its id, if none found => redirect to overview
     * 
     * Locked groups will not be returned!
     * 
     * @param $roleId
     * 
     * @return User_Model_Row_Role
     */
    protected function _checkRoleExists($roleId)
    {
        $role = $this->_model->findById($roleId);
        if(!$role || $role->isLocked()) {
            $this->_messenger->addWarning($this->view->t(
                'Role not found'
            ));
            $this->_goToOverview();
        }
        
        return $role;
    }
}

