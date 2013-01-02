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
 * User_LoginController
 *
 * Login controller
 *
 * @category User
 * @package  Controller
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_LoginController extends Zend_Controller_Action
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
            'module'     => 'default',
            'controller' => 'index',
        ));
      
        $this->_helper->layout->setLayout('layout-well');
        $this->_messenger = $this->_helper->getHelper('Messenger');
        
        $this->_model = new User_Model_Auth();
    }

    public function indexAction()
    {
        // check not already logged in
        if($this->_model->hasAuthenticatedUser()) {
            $this->_redirect($this->_goto);
        }
      
        // login procedure
        $loginForm = $this->_model->getAuthForm();
        $loginForm->setAction($this->view->url());
        $this->view->form = $loginForm;
        
        if (!$this->_request->isPost()) {
            return;
        }
        
        if(!$loginForm->isValid($this->_request->getPost())
            || !$this->_model->authenticateForm($loginForm)
        ) {
            $reset_url = $this->view->url(array(
                'module'     => 'user',
                'controller' => 'reset', 
                'action'     => 'index'
            ), 'default', TRUE);
            $this->_messenger->addError(
                $this->view->t('<strong>Please control your username and password</strong>'),
                array($reset_url => $this->view->t('Forgot your password?'))
            );
            return;
        }
        
        
        // we are logged in
        $this->_messenger->addSuccess(
            $this->view->t('<strong>You successfully logged in!</strong>')
        );
        
        // redirect
        $this->_redirect($this->_goto);
    }
}

