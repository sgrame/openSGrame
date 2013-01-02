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
class User_Admin_ConfigController extends SG_Controller_Action
{
    /**
     * ACL
     */
    protected $_aclResource  = 'user:admin';
    protected $_aclPrivilege = 'administer configuration';
    
    /**
     * Model
     * 
     * @var User_Model_Config
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
       $this->_model = new User_Model_Config();
       $this->_messenger = $this->_helper->getHelper('Messenger');
       
       $this->view->layout()->title = $this->view->t('User configuration');
       $this->view->layout()->subnavId = 'sub-user-admin';
    }

    public function indexAction()
    {
        $form = $this->_model->getForm();
        $this->view->form = $form;
        
        if (!$this->_request->isPost()) {
            return;
        }
        
        if (!$form->isValid($this->_request->getParams())) {
            return;
        }
        
        $this->_model->saveForm($form);
        $this->_messenger->addSuccess($this->view->t('Configuration saved.'));
        
        // redirect after save
        $this->_gotoDestination();
        $this->_redirect($this->view->url(array(
            'module'     => 'user',
            'controller' => 'config',
            'action'     => 'index',
        ), 'admin', true));
    }


}

