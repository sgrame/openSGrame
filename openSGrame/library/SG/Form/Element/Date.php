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
 * SG_Form_Element_Date
 *
 * Date picker form element
 * Form element with an extra class so it is detected as an date field
 *
 * @category SG
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Form_Element_Date extends Zend_Form_Element_Xhtml
{
    /**
     * Default form view helper to use for rendering
     * @var string
     */
    public $helper = 'formDate';
    
    /**
     * The form element value
     * 
     * @var Zend_Date
     */
    protected $_value;
    
    /**
     * The display format of the date
     * 
     * @see Zend_Date for possible formats
     * or
     * @link http://framework.zend.com/manual/en/zend.date.constants.html
     * 
     * @var string
     */
    protected $_displayFormat = Zend_Date::DATE_MEDIUM;
    
    /**
     * Element init
     */
    public function init()
    {
        $this->setAttrib('maxlength', 10);
    }
    
    /**
     * Set the date format
     * 
     * @param string $format
     * 
     * @return Zend_Form_Element
     */
    public function setDisplayFormat($format)
    {
        $this->_displayFormat = $format;
        
        return $this;
    }
    
    /**
     * Get the date format
     * 
     * @param void
     * 
     * @return string
     */
    public function getDisplayFormat()
    {
        return $this->_displayFormat;
    }
    
    /**
     * Set element value
     *
     * @param  mixed $value
     *     Date string (yyyy-MM-dd) or Zend_Date object
     * 
     * @return Zend_Form_Element
     */
    public function setValue($value)
    {
        if($value instanceof Zend_Date) {
            $this->_value = $value;
            return $this;
        }
        
        if(empty($value)) {
            $this->_value = null;
            return $this;
        }
        
        $validator = new Zend_Validate_Date();
        if(!$validator->isValid($value)) {
            $this->_value = $value;
        }
        
        $date = new Zend_Date($value, 'yyyy-MM-dd');
        $this->_value = $date;
        
        return $this;
    }
    
    /**
     * Get the value
     * 
     * @param void
     * 
     * @return mixed
     *     String 
     */
    public function getValue()
    {
        if($this->_value instanceof Zend_Date) {
            return $this->_value->get('yyyy-MM-dd');
        }

        return $this->_value;
    }
    
    /**
     * Get the date object
     * 
     * @param void
     * 
     * @return Zend_Date
     */
    public function getDate()
    {
        return $this->_value;
    }
    
    /**
     * Validate the date value
     * 
     * If we get the value to validate, a string in the displayFormat will 
     * be given. We need to transform that back to the value we need before 
     * passing it trough the filters and validators 
     */
    public function isValid($value, $context = null)
    {
        if(!empty($value)) {
            if(!$this->_checkIsInDisplayFormat($value)) {
                $this->_value = $value;
                $this->addError('Not a valid date');
                return false;
            }
          
            $date = new Zend_Date($value, $this->getDisplayFormat());
            $value = $date->get('yyyy-MM-dd');
        }
        
        return parent::isValid($value, $context);
    }
    
    /**
     * Render form element
     *
     * @param  Zend_View_Interface $view
     * @return string
     */
    public function render(Zend_View_Interface $view = null)
    {
        if ($this->_isPartialRendering) {
            return '';
        }

        if (null !== $view) {
            $this->setView($view);
        }
        
        // we need to have a date element with the date value as the 
        // displayFormat requests
        $value = $this->_value;
        $this->_value = $this->getDate()->get($this->getDisplayFormat());
        $date = clone $this;
        $this->_value = $value;

        $content = '';
        foreach ($this->getDecorators() as $decorator) {
            $decorator->setElement($date);
            $content = $decorator->render($content);
        }
        return $content;
    }
    
    /**
     * Helper to check if given value matches the displayFormat
     * 
     * @param string $value
     * 
     * @return matches
     */
    protected function _checkIsInDisplayFormat($value)
    {
        $validator = new Zend_Validate_Date();
        $validator->setFormat($this->getDisplayFormat());
        return $validator->isValid($value);
    }
}
