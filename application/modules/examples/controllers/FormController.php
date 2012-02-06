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
 * Examples_FormController
 *
 * Examples of the Bootstrap Forms integration
 *
 * @category Examples
 * @package  Controller
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class Examples_FormController extends Zend_Controller_Action
{
    /**
     * @var TB_Controller_Action_Helper_Messenger
     */
    protected $_messenger;

    public function init()
    {
        $this->_messenger = $this->_helper->getHelper('Messenger');
        $this->view->layout()->title = 'Test form';
    }

    public function indexAction()
    {
        $testForm = new Examples_Form_Bootstrap();
        $testForm->setAction($this->view->url());
        $this->view->form = $testForm;
        
        if (!$this->_request->isPost()) {
            return;
        }
        
        if ($testForm->isValid($this->_request->getPost())) {
            // fetch values
            $values = $testForm->getValues();
            
            $this->_messenger->addSuccess(
                array(
                    '<strong>You successfully posted the test form</strong>',
                    'Was it hard to complete?',
                ),
                array(
                    'http://www.google.com' => 'Use the search Luke',
                )
            );
            
            return;
        }
        
        // try again?
        if($testForm->getElement('again')->isChecked()) {
            $testForm = new Examples_Form_Bootstrap();
            $this->view->form = $testForm;
            return;
        }
        
        // form errors
        $this->_messenger->addError(array(
            '<strong>Please control your input!</strong>',
            'Complete or fill in the fields marked in red.'
        ));
    }
}

