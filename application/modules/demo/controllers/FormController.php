<?php
/**
 * Demo of Twitter Bootstrap forms
 * 
 */
class Demo_FormController extends Zend_Controller_Action
{
    /**
     * @var SG_Controller_Action_Helper_Messenger
     */
    protected $_messenger;

    public function init()
    {
        $this->_messenger = $this->_helper->getHelper('Messenger');
        $this->view->layout()->title = 'Test form';
    }

    public function indexAction()
    {
        $testForm = new Demo_Form_Demo();
        $testForm->setAction($this->view->url());

        if ($this->_request->isPost()) {
            if ($testForm->isValid($this->_request->getPost())) {

                // fetch values
                $values = $testForm->getValues();
                
                $this->_messenger->addSuccess(
                  array(
                      '<strong>You successfully registrated as a new user!</strong>',
                      'Check your mailbox for the user account details.',
                  ),
                  array(
                      '/user/login' => 'Go to login',
                  )
                );

                $this->_messenger->addInfo('Only info here.');
            }

            // print error
            else {
                $this->_messenger->addError(array(
                    '<strong>Please control your input!</strong>',
                    'Complete or fill in the fields marked in red.'
                ));
                $this->_messenger->addWarning('Oh no a warning!');
              
                $this->view->messages = array('error', 'Please control your input!'); // extra message on top
            }
        }
        
        $this->view->form = $testForm;
    }
}

