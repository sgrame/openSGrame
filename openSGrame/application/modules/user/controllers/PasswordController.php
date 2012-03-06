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
 * Password change controller
 *
 * @category User
 * @package  Controller
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_PasswordController extends SG_Controller_Action
{
    /**
     * ACL
     */
    protected $_aclResource  = 'user';
    protected $_aclPrivilege = 'edit profile';
  
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
     * The namespace for the password reset session
     * 
     * @var string
     */
    const NS_PASSWORD_RESET = 'user:password-reset';
    
    
    /**
     * Initiate the controller
     */
    public function init()
    {
        $this->_messenger = $this->_helper->getHelper('Messenger');
        $this->_model = new User_Model_User();
    }
    
    /**
     * Change the user password
     */
    public function indexAction()
    {
        $form = new User_Form_Password();
        $this->view->form = $form;
        
        // check for password reset
        $ns = false;
        if(Zend_Session::namespaceIsset(self::NS_PASSWORD_RESET)) {
            $ns = new Zend_Session_Namespace(self::NS_PASSWORD_RESET);
        }
        if($ns && !empty($ns->uuid)) {
            $userAction = $this->_model->getUserActionByUuid($ns->uuid);
            if($userAction) {
                $form->removeElement('old_pwd');
            }
        }
        
        if (!$this->_request->isPost()) {
            return;
        }
        
        if(!$form->isValid($this->_request->getPost())) {
            $this->_messenger->addError($this->view->t(
                '<strong>Check form values</strong>'
            ));
            return;
        }
        
        // change the password
        $auth = new User_Model_Auth();
        $user = $auth->getAuthenticatedUser();
        $this->_model->changeUserPassword($user, $form->getValue('new_pwd'));
        
        // remove the namespace (if any)
        if($ns && !empty($userAction)) {
            Zend_Session::namespaceUnset(self::NS_PASSWORD_RESET);
            $userAction->setUsed();
            $userAction->save();
        }
        
        $this->_messenger->addSuccess($this->view->t(
            '<strong>Password is changed</strong>'
        ));
       
        $this->_redirect($this->view->url());
    }
}

