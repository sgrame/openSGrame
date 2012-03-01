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
 * User_Admin_PermissionsController
 *
 * Users configuration
 *
 * @category User_Admin
 * @package  Controller
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_Admin_PermissionsController extends Zend_Controller_Action
{
    /**
     * Model
     * 
     * @var User_Model_Permission
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
       $this->_model = new User_Model_Permission();
       $this->_messenger = $this->_helper->getHelper('Messenger');
       
       $this->view->layout()->title = $this->view->t('Manage permissions');
       
       // set the subnavigation
       $this->view->layout()->subnavId = 'sub-user-admin';
    }

    public function indexAction()
    {
        $form = $this->_model->getPermissionsForm();
        $this->view->form = $form;
        
        if(!$this->_request->isPost()) {
            return;
        }
        
        if (!$form->isValid($this->_request->getPost())) {
            return;
        }
        
        // save the values
        if(!$this->_model->savePermissionsForm($form)) {
            $this->_messenger->addError($this->view->t(
                'There was a problem saving the permissions data, try again or contact platform administrator.'
            ));
            return;
        }
        
        // we updated the permissions
        $this->_messenger->addSuccess($this->view->t(
            'Permissions are saved.'
        ));
        
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
            'controller' => 'permissions',
            'action'     => 'index',
        ), 'admin', true));
    }
}

