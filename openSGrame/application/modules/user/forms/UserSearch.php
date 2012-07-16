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
 * User_Form_UserSearch
 *
 * Search within users form
 *
 * @category User
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class User_Form_UserSearch extends SG_Form
{
    /**
     * Configure user form.
     *
     * @return void
     */
    public function init()
    {
        // form config
        $this->setAttrib('id', 'user-form');

        // create elements
        $firstname  = new Zend_Form_Element_Text('firstname');
        $firstname  ->setLabel('Firstname');
        
        $lastname  = new Zend_Form_Element_Text('lastname');
        $lastname  ->setLabel('Lastname');
        
        $username  = new Zend_Form_Element_Text('username');
        $username  ->setLabel('Username');
        
        $email     = new Zend_Form_Element_Text('email');
        $email     ->setLabel('email');
                   
        $groups    = new User_Form_Element_SelectGroupsFilter('groups');
        $groups    ->setLabel('Group');
        
        $roles     = new User_Form_Element_SelectRolesFilter('roles');
        $roles     ->setLabel('Role'); 
        
        $status    = new User_Form_Element_SelectStatusFilter('status');
        $status    ->setLabel('Status');
        
        $submit    = new Zend_Form_Element_Submit('submit');
        $submit    ->setLabel('Search');
        
        $showall   = new Zend_Form_Element_Submit('showall');
        $showall   ->setLabel('Show all');

        // add elements
        $this->addElements(array(
            $firstname,
            $lastname,
            $username, 
            $email, 
            $groups,
            $roles,
            $status,
            $submit,
            $showall,
        ));
        
        // Search params
        $this->addDisplayGroup(
            array('firstname', 'lastname', 'username', 'email', 'groups', 'roles', 'status'),
            'search'
        );
        $this->getDisplayGroup('search')->setLegend('Search users');
        
        // button group
        $this->addButtonGroup(
            array('submit', 'showall'),
            'submit'
        );
    }

    /**
     * Get the search array out of the form values
     * 
     * @param void
     * 
     * @return array
     */
    public function getSearchValues()
    {
        $values = $this->getValues();
        $elementNames = $this->_getSearchElementNames();
        
        foreach ($values AS $key => $value) {
            if(!in_array($key, $elementNames)) {
                unset($values[$key]);
            }
        }
        
        return $values;
    }
    
    /**
     * Helper to get the element names of the search fields
     * 
     * @return array
     */
    protected function _getSearchElementNames()
    {
        static $names;
        
        if(!is_array($names)) {
            $names = array();
          
            $elements = $this->getElements();
            
            foreach($elements AS $name => $element) {
                if($element instanceof Zend_Form_Element_Submit) {
                    continue;
                }
                
                $names[] = $name;
            }
        }
        
        return $names;  
    }
    
}

