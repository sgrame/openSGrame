<?php
/**
 * @category TB
 * @package  TB_Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * TB_Form_Decorator_BootstrapErrors
 *
 * Based on Ez_Form_Decorator_BootstrapErrors
 * Wraps errors in span with class help-inline
 *
 * @category   TB
 * @package    TB_Form
 * @subpackage Decorator
 * @author     Peter Decuyper <sgrame@gmail.com>
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @link       https://github.com/sgrame/openSGrame
 */
class TB_Form_Decorator_BootstrapErrors extends Zend_Form_Decorator_Errors
{
    /**
     * Render content wrapped in an HTML tag
     *
     * @param  string $content
     * @return string
     */
    public function render($content)
    {
        $element = $this->getElement();
        $view    = $element->getView();
        if (null === $view) {
            return $content;
        }

        $errors = $element->getMessages();
        if (empty($errors)) {
            return $content;
        }

        $separator = $this->getSeparator();
        $placement = $this->getPlacement();
        $formErrorHelper = $view->getHelper('formErrors');
        $formErrorHelper->setElementStart('<span%s>')
            ->setElementSeparator('<br />')
            ->setElementEnd('</span>');
        $errors = $formErrorHelper->formErrors($errors, array('class' => 'help-block'));

        switch ($placement) {
            case 'PREPEND':
                return $errors . $separator . $content;
            case 'APPEND':
            default:
                return $content . $separator . $errors;
        }
    }

}