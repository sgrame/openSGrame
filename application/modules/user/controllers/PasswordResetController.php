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
 * User_PasswordResetController
 *
 * Password recovery controller
 *
 * @category User
 * @package  Controller
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_PasswordResetController extends Zend_Controller_Action
{
    /**
     * The authentication model
     * 
     * @var User_Model_Auth
     */
    protected $_model;
    
    /**
     * The messenger
     * 
     * @var TB_Controller_Action_Helper_Messenger
     */
    protected $_messenger;
    
    
    /**
     * Initiate the controller
     */
    public function init()
    {
        $this->_helper->layout->setLayout('layout-login');
        $this->_messenger = $this->_helper->getHelper('Messenger');
        
        $this->_model = new User_Model_Auth();
    }

    public function indexAction()
    {
        $form = new User_Form_PasswordReset();
        $form->setAction($this->view->url());
        
        $this->view->form = $form;
    }


}

