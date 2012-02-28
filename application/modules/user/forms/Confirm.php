<?php
/**
 * @category User
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * User_Form_User
 *
 * User creation form
 *
 * @category User
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_Form_Confirm extends SG_Form
{
    /**
     * Configure user form.
     *
     * @return void
     */
    public function init()
    {
        // form config
        $this->setAttrib('id', 'user-confirm');
        
        $note    = new SG_Form_Element_Note('note');
        
        $submit  = new Zend_Form_Element_Submit('submit');
        $submit  ->setLabel('Confirm');
        
        $cancel  = new Zend_Form_Element_Submit('cancel');
        $cancel  ->setLabel('Cancel');

        // add elements
        $this->addElements(array(
            $note, 
            $submit,
            $cancel,
        ));
        
        // Confirmation group
        $this->addDisplayGroup(
            array('note'),
            'confirm'
        );
        $this->getDisplayGroup('confirm')->setLegend('You need to confirm this action');
        
        // button group
        $this->addButtonGroup(
            array('submit', 'cancel'),
            'submit'
        );
        
        $this->addElement('hash', 'confirm_csrf', array('salt' => __CLASS__));
        $this->addElement('hidden', 'id');
    }

    
}

