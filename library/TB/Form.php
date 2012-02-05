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
     * Sets the Twitter Bootstrap decorator as default for all form elements
     */
    public function __construct($options = null) {
        // add the Form decorator
        $this->addElementPrefixPath(
                 'TB_Form_Decorator',
                 'TB/Form/Decorator/',
                 Zend_Form_Element::DECORATOR
             );
             
        // set form type to default horizontal
        if(!isset($_options['attribs']['class'])) {
            $this->setAttrib('class', 'form-horizontal');
        }
             
        parent::__construct($options);
    }
  
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
}