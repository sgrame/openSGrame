<?php
/**
 * @category SG
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Form_Sortable
 *
 * Generic form to save sortables
 *
 * @category SG
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Form_Sortable extends SG_Form
{
    /**
     * The hidden input field id (for the sortable value)
     *  
     * @var string
     */
    protected $_sortableId = 'sortable_data';
    
    /**
     * Has the form a cancel button
     * 
     * @var bool 
     */
    protected $_hasCancelButton = TRUE;
    
    /**
     * Configure user form.
     *
     * @return void
     */
    public function init()
    {
        $sortable = new SG_Form_Element_Sortable($this->_sortableId);
        
        $submit     = new Zend_Form_Element_Submit('submit');
        $submit     ->setLabel('Save');
        
        $elements = array($sortable, $submit);
        $buttons  = array('submit');
        
        if($this->_hasCancelButton) {
            $cancel = new Zend_Form_Element_Submit('cancel');
            $cancel ->setLabel('Cancel');
            $elements[] = $cancel;
            $buttons[]  = 'cancel';
        }

        // add elements
        $this->addElements($elements);
        
        // button group
        $this->addButtonGroup($buttons, 'submit');
        
        $this->addElement('hash', 'sortable_csrf', array(
            'salt' => 'sortable-' . $this->_sortableId
        ));
    }
    
    /**
     * Get the sortable form element
     * 
     * @param void
     * 
     * @return SG_Form_Element_Sortable 
     */
    public function getElementSortable()
    {
        return $this->getElement($this->_sortableId);
    }
    
    /**
     * Set form state from options array
     *
     * @param  array $options
     * @return Zend_Form
     */
    public function setOptions(array $options) {
        // check and set the inputId of the sortable input field
        if (isset($options['sortableId'])) {
            $this->_sortableId = (string)$options['sortableId'];
            unset($options['sortableId']);
        }
        
        // check and set the inputId of the sortable input field
        if (isset($options['hasCancelButton'])) {
            $this->_hasCancelButton = (bool)$options['hasCancelButton'];
            unset($options['hasCancelButton']);
        }
        
        parent::setOptions($options);
    }
}

