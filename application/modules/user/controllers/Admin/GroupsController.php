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
class User_Admin_GroupsController extends Zend_Controller_Action
{
    /**
     * Model
     * 
     * @var User_Model_Group
     */
    protected $_model;
  
    /**
     * Redirect to login if no access
     */
    public function init()
    {
       $this->_model = new User_Model_Group();
       $this->view->layout()->title = $this->view->t('Manage groups');
       
       // set the subnavigation
       $this->view->layout()->subnavId = 'sub-user-admin';
    }

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


}

