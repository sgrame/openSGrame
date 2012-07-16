<?php
/**
 * @category SG
 * @package  Payment
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Payment_Ogone_Form
 *
 * Ogone form
 *
 * @category SG
 * @package  Payment
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Payment_Ogone_Form extends SG_Form
{
    /**
     * The Ogone payment object
     * 
     * @var SG_Payment_Ogone 
     */
    protected $_payment;
    
    /**
     * Constructor 
     * 
     * @param SG_Payment_Ogone $payment
     */
    public function __construct($payment)
    {
        $this->_payment = $payment;
        
        parent::__construct();
    }
    
    /**
     * Configure user form.
     *
     * @return void
     */
    public function init()
    {
        // form config
        $this->setAttrib('id', 'sg-payment-ogone-form');

        // action
        $this->setAction($this->_payment->getEndpoint());
        
        // add the hidden fields
        $fields = $this->_payment->getData();
        
        foreach ($fields AS $columnName => $value) {
            $this->addElement('hidden', $columnName, array(
                'value' => $value
            ));
        }
        
        // add the hash
        $this->addElement('hidden', 'SHASign', array(
            'value' => $this->_payment->getHash()
        ));
        
        // submit button
        $submit     = new Zend_Form_Element_Submit('submit');
        $submit     ->setLabel('Next');
        
        // add elements
        $this->addElements(array(
            $submit,
        ));
        
        // button group
        $this->addButtonGroup(
            array('submit'),
            'submit'
        );
    }
}

