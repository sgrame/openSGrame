<?php
/**
 * @category Examples
 * @package  Controller
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * Examples_MessengerController
 *
 * Examples of the Messenger integration
 *
 * @category Examples
 * @package  Controller
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class Examples_MessengerController extends Zend_Controller_Action
{
    /**
     * @var TB_Controller_Action_Helper_Messenger
     */
    protected $_messenger;

    public function init()
    {
        $this->_messenger = $this->_helper->getHelper('Messenger');
        $this->view->layout()->title = 'Test messenger';
    }

    public function indexAction()
    {
        $type = $this->getRequest()->getParam('type');
        
        switch($type) {
            case 'success':
                $this->_messenger->addSuccess(
                    '<strong>Success</strong> message triggered'
                );
                break;
            case 'success-action':
                $this->_messenger->addSuccess(
                    array(
                        '<strong>You got the success action</strong>',
                        'Extra text goes after it',
                        '<p>HTML is allowed</p>',
                    ),
                    array(
                        'http://www.google.com' => 'Use the search Luke',
                    )
                );
                break;
            
            case 'warning':
                $this->_messenger->addWarning('Warning message');
                break;
            case 'warning-action':
                $this->_messenger->addWarning('Warning message', array(
                    'http://twitter.github.com/bootstrap/' => 'Visit Twitter Bootstrap',
                ));
                break;

            case 'error':
                $this->_messenger->addError(
                    '<strong>Ohoh</strong>, something went wrong'
                );
                break;
            case 'error-action':
                $this->_messenger->addError(
                    '<strong>Ohoh</strong>, something went wrong',
                    array(
                       '/examples/messenger/index/type/error-action' => 'Try again'
                    )
                );
                break;
              
            case 'mixed':
                $this->_messenger->addInfo('Multiple messages at once');
                $this->_messenger->addSuccess('Multiple messages at once');
                $this->_messenger->addWarning('Multiple messages at once');
                $this->_messenger->addError('Multiple messages at once', array(
                    '/examples/messenger' => 'Reset',
                    '/examples/messenger/index/type/mixed' => 'Try again',
                ));
                break;
            
            case 'info':
                $this->_messenger->addInfo(array(
                    '<p><strong>Default info message</strong></p>',
                    '<p>Want to see a <a href="/examples/messenger/index/type/success">Success message</a>?</p>',
                ));
                break;
              
            case 'info-action':
            default:
                $this->_messenger->addInfo(
                    '<strong>Info message</strong> with action',
                    array(
                        '/examples/messenger/index/type/info' => 'View default info message',
                    )
                );
                break;
        }

        
    }

}

