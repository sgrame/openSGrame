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
     * Redirect to login if no access
     */
    public function init()
    {
       $this->_model = new User_Model_User();
       $this->view->layout()->title = $this->view->t('Manage users');
       
       // set the subnavigation
       $this->view->layout()->subnavId = 'sub-user-admin';
    }

    /**
     * Users overview
     */
    public function indexAction()
    {
        $users = $this->_model->getUsers();
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
        $form = new User_Form_User();
        $form->setAction($this->view->url());
        $this->view->form = $form;
        
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
}

