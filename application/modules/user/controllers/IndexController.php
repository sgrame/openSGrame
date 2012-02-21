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
 * User_IndexController
 *
 * Login controller
 *
 * @category User
 * @package  Controller
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_IndexController extends Zend_Controller_Action
{
    /**
     * Redirect to login if no access
     */
    public function init()
    {
        $auth = Zend_Auth::getInstance();
        if(!$auth->hasIdentity()) {
            $this->_redirect($this->view->url(array(
                'module'     => 'user',
                'controller' => 'login',
            )));
        }
    }

    public function indexAction()
    {
        // action body
    }


}

