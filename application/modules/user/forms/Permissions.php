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
 * User_Form_Permissions
 *
 * Manage the permissions for the role(s)
 *
 * @category User
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_Form_Permissions extends SG_Form
{
    /**
     * Setup form.
     *
     * @return void
     */
    public function init()
    {
        // form config
        $this->setAttrib('id', 'user-permissions-form');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit ->setLabel('Save');
        
        // add elements
        $this->addElements(array(
            $submit,
        ));
        
        // button group
        $this->addButtonGroup(
            array('submit'),
            'submit'
        );
        $this->getDisplayGroup('actions')->setOrder(9999);
        $this->addElement('hash', 'login_csrf', array(
          'salt' => 'user-permissions-form'
        ));
        
        // set the table decorator
        $this->setDisableLoadDefaultDecorators(true);
        $this->setDecorators(array(
            'FormElements', 
            array(
                'SimpleTable', array(
                    'class'   => 'table table-striped',
                    'columns' => array()
                )
            ), 
            'Form')
        );
    }
    
    /**
     * Add the table header
     * 
     * @param array
     * 
     * @return void
     */
    public function setRowHeader($labels)
    {
        $labels = array_merge(array(NULL), $labels);
        $this->getDecorator('SimpleTable')->setColumns($labels);
    }
    

    /**
     * Add a row to the form
     * 
     * @param string $label
     *     Label for the row (permission name)
     * @param int $id
     *     Id for the row (permission ID)
     * @param array $roleIds
     *     Array containing the id's for the several rows
     *     
     * @return SG_Form
     *     The subform
     */
    public function addRow($label, $permissionId, $roleIds)
    {
        $rowForm  = new Zend_Form();
        $elements = array();
        
        // add the label
        $rowLabel = new SG_Form_Element_Note('label');
        $rowLabel ->setValue($label);
        $elements[] = $rowLabel;
        
        // add the roles
        foreach($roleIds AS $id) {
          $name = 'role_' . $id;
          $role = new Zend_Form_Element_Checkbox($name);
          $role->setCheckedValue($permissionId);
          $elements[] = $role;
        }
        
        // add the elements
        $rowForm->addElements($elements);
        
        
        
        $rowForm->setDecorators(array(
          'FormElements',
          array('HtmlTag', array('tag' => 'tr'))
        ));
        $rowForm->setElementDecorators(array(
          'ViewHelper', 
          array('HtmlTag', array('tag' => 'td'))
        ));
        
        // set the index (equal to permission id)
        $rowIndex = 'perm_' . (int)$permissionId;
        $rowForm->setElementsBelongTo($rowIndex);
        $this->addSubForm($rowForm, $rowIndex);
        
        return $rowForm;
    }
}

