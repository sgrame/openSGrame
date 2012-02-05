<?php
/**
 * @category TB
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * TB_Form
 *
 * Extends Zend_Form
 * Is based on EasyBib (https://github.com/easybib/EasyBib_Form_Decorator)
 * - provides integration with Twitter Bootstrap v2
 * - provides buildErrorDecorators method
 *   for adding css error classes to form if not valid
 *
 * @category TB
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class TB_Form extends Zend_Form
{
    /**
     * Proxy to Zend_Form::isValid()
     * calls buildErrorDecorators for parent::isValid() returning false
     *
     * @param  array $data
     * @return boolean
     */
    public function isValid($values)
    {
        $validCheck = parent::isValid($values);
        
        if ($validCheck === false) {
            $this->buildErrorDecorators();
        }
        
        return $validCheck;
    }

    /**
     * Adds error class to elements that are not valid
     * 
     * @param void
     * @return void
     */
    public function buildErrorDecorators() {
        foreach ($this->getErrors() AS $key=>$errors) {
            $htmlTagDecorator = $this->getElement($key)->getDecorator('HtmlTag');
            if (empty($htmlTagDecorator)) {
                continue;
            }
            if (empty($errors)) {
                continue;
            }
            $class = $htmlTagDecorator->getOption('class');
            $htmlTagDecorator->setOption('class', $class . ' error');
        }
    }
    
    /**
     * Render form
     *
     * @param  Zend_View_Interface $view
     * @return string
     */
    public function render(Zend_View_Interface $view = null)
    {
        TB_Form_Decorator::setFormDecorator($this, TB_Form_Decorator::BOOTSTRAP);
        return parent::render($view);
    }
    
    /**
     * Add a button group
     *
     * Groups named elements for display purposes.
     * It will group them in a div to group form action buttons
     *
     * If a referenced element does not yet exist in the form, it is omitted.
     *
     * @param  array $elements
     * @param  string $primaryButton
     * @param  array|Zend_Config $options
     * @return Zend_Form
     * @throws Zend_Form_Exception if no valid elements provided
     */
    public function addButtonGroup(array $elements, $primaryButton, $options = null)
    {
        // set the class
        $options['attribs']['class'] = array('form-actions');
        
        // set the primary class
        $primary = $this->getElement($primaryButton);
        if($primary) {
            $class = $primary->getAttrib('class');
            if(!$class) {
                $class = array();
            }
            $primary->setAttrib(
                'class', 
                array_unique(array_merge(array('btn-primary'), $class))
            );
        }
        
        $this->addDisplayGroup($elements, 'actions', $options);
    }
}