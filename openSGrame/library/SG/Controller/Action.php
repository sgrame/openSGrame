<?php
/**
 * @category SG
 * @package  Controller
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Controller_Action
 *
 * Basic controller for the openSGrame application
 * Adds automatic access check by defining 2 parameters in the controller class:
 *   (string) $_aclResource
 *   (string) $_aclPrivilege
 * The automatic access check will only be performed if both are set.
 * 
 * You can override the automatic access check validaton by overriding the 
 * _aclCheck() method.
 * eg check for resource:permission1 or resource:permission2.
 * 
 * WARNING: 
 * The _aclValidation() will throw a SG_Controller_Action_NotAuthorized_Exception 
 * exception when the ACL requirements are not met.
 *
 * @category SG
 * @package  Controller
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Controller_Action extends Zend_Controller_Action
{
    /**
     * The ACL resource
     * 
     * @var SG_Acl
     */
    protected $_acl;
  
    /**
     * The acl resource 
     * (module name in the user_permission table)
     * 
     * @var string
     */
    protected $_aclResource = null;
    
    /**
     * The acl privilege
     * (name in the user_permission table)
     * 
     * @var string
     */
    protected $_aclPrivilege = null;

    /**
     * Class constructor
     *
     * Adds the ACL validation call to the controller.
     * 
     * See Zend_Controller::__construct() for further documentation.
     *
     * @param Zend_Controller_Request_Abstract $request
     * @param Zend_Controller_Response_Abstract $response
     * @param array $invokeArgs Any additional invocation arguments
     * @return void
     */
    public function __construct(
        Zend_Controller_Request_Abstract $request, 
        Zend_Controller_Response_Abstract $response, 
        array $invokeArgs = array())
    {
        $this->setRequest($request)
             ->setResponse($response)
             ->_setInvokeArgs($invokeArgs);
        $this->_helper = new Zend_Controller_Action_HelperBroker($this);
        
        // perform the access check first!
        $this->_acl = Zend_Registry::get('acl');
        $this->_aclValidation();
        $this->init();
    }
    
    /**
     * Validates if the current user may access the controller.
     * 
     * @param void
     * 
     * @return void
     * @throws SG_Controller_Action_NotAuthorized_Exception
     */
    protected function _aclValidation()
    {
        if(!$this->_aclResource && !$this->_aclPrivilege) {
            return;
        }
        
        if(!$this->_acl->isUserAllowed(
            $this->_aclResource, 
            $this->_aclPrivilege
        )) {
            $fullResource = array(
                $this->_request->getModuleName(),
                $this->_request->getControllerName(),
                $this->_request->getActionName(),
            );
                            
            throw new SG_Controller_Action_NotAuthorized_Exception(
                'User not allowed to access "' 
                . implode(':', $fullResource) 
                . '" (ACL: '
                . $this->_aclResource
                . ' > '
                . $this->_aclPrivilege
                . ').'
            );
        }
    }
}

