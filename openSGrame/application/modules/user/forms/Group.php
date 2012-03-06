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
 * User_Form_Group
 *
 * Group creation form
 *
 * @category User
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_Form_Group extends SG_Form
{
    /**
     * Configure user form.
     *
     * @return void
     */
    public function init()
    {
        // form config
        $this->setAttrib('id', 'user-group-form');

        // create elements
        $name  = new Zend_Form_Element_Text('name');
        $name  ->setLabel('Group name')
               ->setRequired(true);
        
        $submit    = new Zend_Form_Element_Submit('submit');
        $submit    ->setLabel('Save');
        
        $cancel    = new Zend_Form_Element_Submit('cancel');
        $cancel    ->setLabel('Cancel');

        // add elements
        $this->addElements(array(
            $name,
            $submit,
            $cancel,
        ));
        
        // Login group
        $this->addDisplayGroup(
            array('name',),
            'group'
        );
        $this->getDisplayGroup('group')->setLegend('Group');
        
        // button group
        $this->addButtonGroup(
            array('submit', 'cancel'),
            'submit'
        );
        
        $this->addElement('hash', 'login_csrf', array('salt' => 'user-group-form'));
        $this->addElement('hidden', 'group_id');
    }

    /**
     * Validate the form
     *
     * @param  array $data
     * @return boolean
     */
    public function isValid($data)
    {
        $isValid = parent::isValid($data);
        
        if(!$isValid) {
            return;
        }
        
        // group id
        $groupId = $this->getValue('group_id');
        
        $model = new User_Model_DbTable_Group();
        
        // check username exists
        if($model->nameExists($this->getValue('name'), $groupId)) {
            $isValid = false;
            $this->getElement('name')->addError(
                'Name already in use for other group'
            );
            $this->markAsError();
        }
        
        if(!$isValid) {
            $this->buildErrorDecorators();
        }
        
        return $isValid;
    }
}

