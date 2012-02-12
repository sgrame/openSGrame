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
 * User_PasswordController
 *
 * Password recovery controller
 *
 * @category User
 * @package  Controller
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_PasswordController extends Zend_Controller_Action
{
    /**
     * The authentication model
     * 
     * @var User_Model_User
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
        
        $this->_model = new User_Model_User();
    }

    public function resetAction()
    {
        $form = new User_Form_PasswordReset();
        $form->setAction($this->view->url());
        $this->view->form = $form;
        
        if (!$this->_request->isPost()) {
            return;
        }
        
        if(!$form->isValid($this->_request->getPost())) {
            $this->_messenger->addError(
                '<strong>Fill in your username or email</strong>'
            );
            return;
        }
        
        $user = $this->_model->findByUsernameOrEmail($form->getValue('username'));
        if(!$user) {
            $this->_messenger->addError(
                '<strong>We could not find you, check username or email.</strong>'
            );
            return;
        }
        
        if(!$this->_model->resetPassword($user)) {
            $this->_messenger->addWarning(
                array(
                    '<strong>Something went wrong while sending out the password reset email.</strong>',
                    'Contact an administrator or try again later'
                )
            );
            return;
        }
        
        // password recovery mail sent
        $this->_messenger->addSuccess(
            array(
                '<strong>Password reset email send!</strong>',
                'Check your mail.'
            )
        );
    }

    /**
     * Token (pasword reset complete) action
     */
    public function actionAction()
    {
        var_dump($this->getRequest()->getParams());
    }
}

