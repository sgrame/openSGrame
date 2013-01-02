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
 * User_LogoutController
 *
 * Logout controller
 *
 * @category User
 * @package  Controller
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_LogoutController extends Zend_Controller_Action
{
    /**
     * Redirect after login
     * 
     * @var string
     */
    protected $_goto;
    
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
        $this->_goto = $this->view->url(array(
          'controller' => 'login',
        ));
      
        $this->_messenger = $this->_helper->getHelper('Messenger');
        $this->_model = new User_Model_Auth();
    }

    /**
     * Log the user out
     */
    public function indexAction()
    {
        $this->_model->unsetAuth();
        // we are logged in
        $this->_messenger->addInfo(
            $this->view->t('<strong>You are now logged out</strong>')
        );
        $this->_redirect($this->_goto);
    }
}

