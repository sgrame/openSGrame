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
 * User_Admin_GroupsController
 *
 * Users management
 *
 * @category User_Admin
 * @package  Controller
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_Admin_GroupsController extends SG_Controller_Action
{
    /**
     * ACL
     */
    protected $_aclResource  = 'user:admin';
    protected $_aclPrivilege = 'administer groups';
    
    /**
     * Model
     * 
     * @var User_Model_Group
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
       $this->_model = new User_Model_Group();
       $this->_messenger = $this->_helper->getHelper('Messenger');
       
       $this->view->layout()->title = $this->view->t('Manage groups');
       
       // set the subnavigation
       $this->view->layout()->subnavId = 'sub-user-admin';
    }


    /**
     * Show the overview of all groups
     */
    public function indexAction()
    {
        $groups = $this->_model->getGroups(
            $this->_request->getParam('page', 0),
            $this->_request->getParam('order', 'name'),
            $this->_request->getParam('direction', 'asc'),
            $this->_request->getParams()
        );
        $this->view->groups = $groups;
    }

    /**
     * Add a new group
     */
    public function addAction()
    {
        $this->view->layout()->title = $this->view->t('Add group');
        
        $form = $this->_model->getGroupForm();
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
        $group = $this->_model->saveGroupForm($form);
        if(!$group) {
            $this->_messenger->addError($this->view->t(
                'There was a problem saving the group, try again or contact platform administrator.'
            ));
            return;
        }
            
        // we created the user
        $this->_messenger->addSuccess($this->view->t(
            'Group <strong>%s</strong> created', $group->name
        ));
        
        $this->_goToOverview();
    }

    /**
     * Edit an existing group
     */
    public function editAction()
    {
        // try to get the group
        $group = $this->_checkGroupExists($this->getRequest()->getParam('id'));
                
        $this->view->layout()->title = $this->view->t(
            'Edit group <em>%s</em>', $group->name
        );
        
        // get the form
        $form = $this->_model->getGroupForm($group);
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
        
        // update the group in the DB
        $group = $this->_model->saveGroupForm($form);
        if(!$group) {
            $this->_messenger->addError($this->view->t(
                'There was a problem saving the group, try again or contact platform administrator.'
            ));
            return;
        }
            
        // we created the group
        $this->_messenger->addSuccess($this->view->t(
            'Group <strong>%s</strong> updated', $group->name
        ));
        
        $this->_goToOverview();
    }

    /**
     * Delete group
     */
    public function deleteAction()
    {
        // try to get the group
        $group = $this->_checkGroupExists($this->getRequest()->getParam('id'));
                
        $this->view->layout()->title = $this->view->t(
            'Delete group <em>%s</em>', $group->name
        );
        
        // get the form
        $form = $this->_model->getGroupConfirmForm('delete', $group);
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
            $name = $group->name;
            $group->delete();
            $this->_messenger->addSuccess($this->view->t(
                'Group <strong>%s</strong> deleted',
                $name
            ));
        }
        
        $this->_goToOverview();
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
            'controller' => 'groups',
            'action'     => 'index',
        ), 'admin', true));
    }
    
    /**
     * Helper to get a group by its id, if none found => redirect to overview
     * 
     * @param $groupId
     * 
     * @return User_Model_Row_Group
     */
    protected function _checkGroupExists($groupId)
    {
        $group = $this->_model->findById($groupId);
        if(!$group) {
            $this->_messenger->addWarning($this->view->t(
                'Group not found'
            ));
            $this->_goToOverview();
        }
        
        return $group;
    }
}

