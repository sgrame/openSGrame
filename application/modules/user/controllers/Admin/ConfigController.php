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
 * User_Admin_ConfigController
 *
 * Users configuration
 *
 * @category User_Admin
 * @package  Controller
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_Admin_ConfigController extends Zend_Controller_Action
{
    /**
     * Model
     * 
     * @var User_Model_Group
     */
    protected $_model;
  
    /**
     * Init this controller
     */
    public function init()
    {
       $this->_model = new User_Model_Group();
       $this->view->layout()->title = $this->view->t('Users configuration');
       
       // set the subnavigation
       $this->view->layout()->subnavId = 'sub-user-admin';
    }

    public function indexAction()
    {
    }


}

