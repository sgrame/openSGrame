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
class User_Admin_RolesController extends Zend_Controller_Action
{
    /**
     * Model
     * 
     * @var User_Model_Role
     */
    protected $_model;
  
    /**
     * Redirect to login if no access
     */
    public function init()
    {
       $this->_model = new User_Model_Role();
       $this->view->layout()->title = $this->view->t('Manage roles');
       
       // set the subnavigation
       $this->view->layout()->subnavId = 'sub-user-admin';
    }

    public function indexAction()
    {
    }


}

