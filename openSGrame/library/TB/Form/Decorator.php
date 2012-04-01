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
 * TB_Form_Decorator
 *
 * Extends Zend_Form
 * Is based on EasyBib (https://github.com/easybib/EasyBib_Form_Decorator)
 * - provides integration with Twitter Bootstrap v2
 * - provides buildErrorDecorators method
 *   for adding css error classes to form if not valid
 *
 * General usage:
 * TB_Form_Decorator::setFormDecorator($form, 'div', 'submit', 'cancel');
 * TB_Form_Decorator::setFormDecorator(
 *   Instance of form,
 *   Decorator Mode - 3 different options:
 *      - TB_Form_Decorator::TABLE     (html table style)
 *      - TB_Form_Decorator::DIV       (div style)
 *      - TB_Form_Decorator::BOOTSTRAP (twitter bootstrap style)
 *   Name of submit button,
 *   Name of cancel button
 * );
 *
 * @category TB
 * @package  TB_Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class TB_Form_Decorator
{
    /**
     * Constants Definition for Decorator
     */
    const TABLE = 'table';

    const DIV = 'div';

    const BOOTSTRAP = 'bootstrap';

    /**
     * Element Decorator
     *
     * @staticvar array
     */
    protected static $_ElementDecorator = array(
        'table' => array(
            'ViewHelper',
            array(
                'Description',
                array(
                    'tag' => '',
                )
            ),
            'Errors',
            array(
                array(
                    'data' => 'HtmlTag'
                ),
                array(
                    'tag' => 'td'
                )
            ),
            array(
                'Label',
                array(
                    'tag' => 'td',
                )
            ),
            array(
                array(
                    'row' => 'HtmlTag'
                ),
                array(
                    'tag' => 'tr'
                )
            )
        ),
        'div' => array(
            array(
                'ViewHelper'
            ),
            array(
                'Description',
                array(
                    'tag'   => 'span',
                    'class' => 'hint'
                )
            ),
            array(
                'Errors'
            ),
            array(
                'Label'
            ),
            array(
                'HtmlTag',
                array(
                    'tag' => 'div'
                )
            )
        ),
        'bootstrap' => array(
            array(
                'ViewHelper'
            ),
            array(
                'BootstrapErrors',
            ),
            array(
                'Description',
                array(
                    'tag'   => 'p',
                    'class' => array('help-block', 'description'),
                )
            ),
            array(
                'BootstrapTag',
                array(
                    'class' => 'controls'
                )
            ),
            array(
                'Label',
                array(
                    'class' => 'control-label'
                )
            ),
            array(
                'HtmlTag',
                array(
                    'tag'   => 'div',
                    'class' => 'control-group'
                )
            )
        )
    );
    
    /**
     * Captcha Decorator
     *
     * @staticvar array
     */
    protected static $_CaptchaDecorator = array(
        'table' => array(
            'Errors', 
            array(
                array(
                    'data' => 'HtmlTag'
                ),
                array(
                    'tag' => 'td'
                )
            ),
            array(
                'Label',
                array(
                    'tag' => 'td'
                )
            ),
            array(
                array(
                    'row' => 'HtmlTag'
                ),
                array(
                    'tag' => 'tr'
                )
            )
        ),
        'div' => array(
            array(
                'Description',
                array(
                    'tag'   => 'span',
                    'class' => 'hint'
                )
            ),
            array(
                'Errors'
            ),
            array(
                'Label'
            ),
            array(
                'HtmlTag',
                array(
                    'tag' => 'div'
                )
            )
        ),
        'bootstrap' => array(
            array(
                'BootstrapErrors'
            ),
            array(
                'Description',
                array(
                    'tag'   => 'p',
                    'class' => array('help-block', 'description'),
                )
            ),
            array(
                'BootstrapTag',
                array(
                    'class' => 'controls'
                )
            ),
            array(
                'Label',
                array(
                    'class' => 'control-label'
                )
            ),
            array(
                'HtmlTag',
                array(
                    'tag'   => 'div',
                    'class' => 'control-group',
                )
            )
        )
    );
    
    /**
     * Multi Decorator
     *
     * @staticvar array
     */
    protected static $_MultiDecorator = array(
        'table' => array(
            'ViewHelper',
            array(
                'Description',
                array(
                    'tag' => '',
                )
            ),
            'Errors',
            array(
                array(
                    'data' => 'HtmlTag'
                ),
                array(
                    'tag' => 'td'
                )
            ),
            array(
                'Label',
                array(
                    'tag' => 'td'
                )
            ),
            array(
                array(
                    'row' => 'HtmlTag'
                ),
                array(
                    'tag' => 'tr'
                )
            )
        ),
        'div' => array(
            array(
                'ViewHelper'
            ),
            array(
                'Description',
                array(
                    'tag'   => 'span',
                    'class' => 'hint'
                )
            ),
            array(
                'Errors'
            ),
            array(
                'Label',
                array(
                    'class' => 'checkbox',
                )
            ),
            array(
                'HtmlTag',
                array(
                    'tag' => 'div'
                )
            )
        ),
        'bootstrap' => array(
            array(
                'ViewHelper',
            ),
            array(
                'BootstrapErrors'
            ),
            array(
                'Description',
                array(
                    'tag'   => 'p',
                    'class' => array('help-block', 'description'),
                )
            ),
            array(
                'BootstrapTag',
                array(
                    'class' => 'controls',
                )
            ),
            array(
                'Label',
                array(
                    'class' => 'control-label',
                )
            ),
            array(
                'HtmlTag',
                array(
                    'tag'   => 'div',
                    'class' => 'control-group'
                )
            )
        )
    );
    
    /**
     * File Decorator
     *
     * @staticvar array
     */
    protected static $_FileDecorator = array(
        'table' => array(
            array('File'),
            'Errors',
            array(
                array(
                    'data' => 'HtmlTag'
                ),
                array(
                    'tag' => 'td'
                )
            ),
            array(
                'Label',
                array(
                    'tag' => 'td'
                )
            ),
            array(
                array(
                    'row' => 'HtmlTag'
                ),
                array(
                    'tag' => 'tr'
                )
            )
        ),
        'div' => array(
            array('File'),
            array(
                'Description',
                array(
                    'tag'   => 'span',
                    'class' => 'hint'
                )
            ),
            array(
                'Errors'
            ),
            array(
                'Label',
                array(
                    'class' => 'checkbox',
                )
            ),
            array(
                'HtmlTag',
                array(
                    'tag' => 'div'
                )
            )
        ),
        'bootstrap' => array(
            array('File'),
            array(
                'BootstrapErrors'
            ),
            array(
                'Description',
                array(
                    'tag'   => 'p',
                    'class' => array('help-block', 'description'),
                )
            ),
            array(
                'BootstrapTag',
                array(
                    'class' => 'controls',
                )
            ),
            array(
                'Label',
                array(
                    'class' => 'control-label',
                )
            ),
            array(
                'HtmlTag',
                array(
                    'tag'   => 'div',
                    'class' => 'control-group'
                )
            )
        )
    );

    /**
     * Submit Element Decorator
     *
     * @staticvar array
     */
    protected static $_SubmitDecorator = array(
        'table' => array(
            'ViewHelper', 
            array(
                array(
                    'data' => 'HtmlTag'
                ),
                array(
                    'tag' => 'td'
                )
            ),
            array(
                array(
                    'row' => 'HtmlTag'
                ),
                array(
                    'tag' => 'tr',
                    'class' => 'buttons'
                )
            )
        ),
        'div' => array(
            'ViewHelper'
        ),
        'bootstrap' => array(
            'ViewHelper',
            array(
                'HtmlTag',
                array(
                    'tag'   => 'div',
                    'class' => 'form-actions',
                    'openOnly' => false
                )
            )
        )
    );

    /**
     * Reset Element Decorator
     *
     * @staticvar array
     */
    protected static $_ResetDecorator = array(
        'table' => array(
            'ViewHelper', 
            array(
                array(
                    'data' => 'HtmlTag'
                ),
                array(
                    'tag' => 'td'
                )
            ),
            array(
                array(
                    'row' => 'HtmlTag'
                ),
                array(
                    'tag' => 'tr'
                )
            )
        ),
        'div' => array(
            'ViewHelper'
        ),
        'bootstrap' => array(
            'ViewHelper',
            array(
                'HtmlTag',
                array(
                    'closeOnly' => false
                )
            )
        )
    );
    
    /**
     * Button decorator
     * 
     * @staticvar array
     */
    protected static $_ButtonDecorator = array(
        'table' => array(
            'ViewHelper', 
            array(
                array(
                    'data' => 'HtmlTag'
                ),
                array(
                    'tag' => 'td'
                )
            ),
            array(
                array(
                    'row' => 'HtmlTag'
                ),
                array(
                    'tag' => 'tr'
                )
            )
        ),
        'div' => array(
            'ViewHelper'
        ),
        'bootstrap' => array(
            'ViewHelper',
        ),
    );
    
    /**
     * Inline checkbox Decorator
     *
     * @staticvar array
     */
    protected static $_CheckboxInlineDecorator = array(
        'table' => array(
            'ViewHelper',
            array(
                'Description',
                array(
                    'tag' => '',
                )
            ),
            'Errors',
            array(
                array(
                    'data' => 'HtmlTag'
                ),
                array(
                    'tag' => 'td'
                )
            ),
            array(
                'Label',
                array(
                    'tag' => 'td',
                )
            ),
            array(
                array(
                    'row' => 'HtmlTag'
                ),
                array(
                    'tag' => 'tr'
                )
            )
        ),
        'div' => array(
            array(
                'ViewHelper'
            ),
            array(
                'Description',
                array(
                    'tag'   => 'span',
                    'class' => 'hint'
                )
            ),
            array(
                'Errors'
            ),
            array(
                'Label'
            ),
            array(
                'HtmlTag',
                array(
                    'tag' => 'div'
                )
            )
        ),
        'bootstrap' => array(
            array(
                'ViewHelper'
            ),
            array(
                'BootstrapErrors',
            ),
            array(
                'Description',
                array(
                    'tag'   => 'p',
                    'class' => array('help-block', 'description'),
                )
            ),
            array(
                'Label',
                array(
                    'class' => 'checkbox'
                )
            ),
        )
    );

    /**
     * Hiden Element Decorator
     *
     * @staticvar array
     */
    protected static $_HiddenDecorator = array(
        'table' => array(
            'ViewHelper'
        ),
        'div' => array(
            'ViewHelper'
        ),
        'bootstrap' => array(
            'ViewHelper'
        )
    );

    /**
     * Form Element Decorator
     *
     * @staticvar array
     */
    protected static $_FormDecorator = array(
        'table' => array(
            'FormElements',
            'Form'
        ),
        'div' => array(
            'FormElements',
            'Form'
        ),
        'bootstrap' => array(
            'FormElements',
            'Form'
        )
    );

    /**
     * DisplayGroup Decorator
     *
     * @staticvar array
     */
    protected static $_DisplayGroupDecorator = array(
        'table' => array(
            'FormElements',
            array(
                'HtmlTag',
                array(
                    'tag' => 'table',
                    'summary' => ''
                )
            ),
            'Fieldset'
        ),
        'div' => array(
            'FormElements',
            'Fieldset'
        ),
        'bootstrap' => array(
            'FormElements',
            'Fieldset'
        )

    );
    
    /**
     * ZendX_Jquery Decorator
     *
     * @staticvar array
     */
    protected static $_JqueryElementDecorator = array(
        'table' => array(
            'UiWidgetElement',
            array(
                'Description',
                array(
                    'tag' => '',
                )
            ),
            'Errors',
            array(
                array(
                    'data' => 'HtmlTag'
                ),
                array(
                    'tag' => 'td'
                )
            ),
            array(
                'Label',
                array(
                    'tag' => 'td'
                )
            ),
            array(
                array(
                    'row' => 'HtmlTag'
                ),
                array(
                    'tag' => 'tr'
                )
            )
        ),
        'div' => array(
            array(
                'UiWidgetElement'
            ),
            array(
                'Description',
                array(
                    'tag'   => 'span',
                    'class' => 'hint'
                )
            ),
            array(
                'Errors'
            ),
            array(
                'Label'
            ),
            array(
                'HtmlTag',
                array(
                    'tag' => 'div'
                )
            )
        ),
        'bootstrap' => array(
            array(
                'UiWidgetElement'
            ),
            array(
                'Description',
                array(
                    'tag'   => 'span',
                    'class' => 'help-block',
                    'style' => 'color: #999;'
                )
            ),
            array(
                'BootstrapErrors'
            ),
            array(
                'BootstrapTag',
                array(
                    'class' => 'controls'
                )
            ),
            array(
                'Label',
                array(
                    'class' => 'control-label'
                )
            ),
            array(
                'HtmlTag',
                array(
                    'tag'   => 'div',
                    'class' => 'control-group'
                )
            )
        )
    );

    /**
     * Set the form decorators by the given string format or by the default div style
     *
     * @param object $objForm        Zend_Form pointer-reference
     * @param string $constFormat    Project_Plugin_FormDecoratorDefinition constants
     * @return NULL
     */
    public static function setFormDecorator(
        Zend_Form $form, $format = self::BOOTSTRAP, $submit_str = 'submit', $cancel_str = 'cancel'
    ) 
    {
        /**
         * - disable default decorators
         * - set form & displaygroup decorators
         */
        $form->setDisableLoadDefaultDecorators(true);
        $form->setDisplayGroupDecorators(self::$_DisplayGroupDecorator[$format]);
        $form->setDecorators(self::$_FormDecorator[$format]);

        // set needed prefix path for bootstrap decorators
        if ($format == self::BOOTSTRAP) {
            $form->addElementPrefixPath(
                'TB_Form_Decorator',
                'TB/Form/Decorator',
                Zend_Form::DECORATOR
            );
            
            // set form type to default horizontal
            $class = $form->getAttrib('class');
            if(empty($class)) {
                $form->setAttrib('class', 'form-horizontal');
            }
        }

        // set form element decorators
        $form->setElementDecorators(self::$_ElementDecorator[$format]);

        // set specific element decorators
        foreach ($form->getElements() as $e) {
            // hidden elements
            $hiddenElements = array(
                'Zend_Form_Element_Hidden', 
                'Zend_Form_Element_Hash'
            );
            if (in_array($e->getType(), $hiddenElements)) {
                $e->setDecorators(self::$_HiddenDecorator[$format]);
            }
            // JQuery
            if (is_subclass_of($e, "ZendX_JQuery_Form_Element_UiWidget")) {
                $e->setDecorators(self::$_JqueryElementDecorator[$format]);
            }
            // captcha
            if ($e instanceof Zend_Form_Element_Captcha) {
                $e->setDecorators(self::$_CaptchaDecorator[$format]);
            }
                
            // multiCheckbox & radios
            if ($e instanceof Zend_Form_Element_MultiCheckbox) {
                self::setMultiDecorator($format, $e, 'checkbox');
            }
            if ($e instanceof Zend_Form_Element_Radio) {
                self::setMultiDecorator($format, $e, 'radio');
            }
            
            // buttons
            if($e instanceof Zend_Form_Element_Submit) {
                $e->setDecorators(self::$_ButtonDecorator[$format]);
                if ($format == self::BOOTSTRAP) {
                    $class = $e->getAttrib('class');
                    if(!$class) {
                        $class = array();
                    }
                    $class = array_unique(array_merge(array('btn'), $class));
                    $e->setAttrib('class', $class);
                }
            }
            
            // files upload
            if($e->getType() == 'Zend_Form_Element_File') {
                $e->setDecorators(self::$_FileDecorator[$format]);
            }
        }
    }

    /**
     * Helper to add the multi decorator to multi elements
     * 
     * @param string $format
     * @param Zend_Form_Element_Multi $element
     * @param string $type multi element type (checkboxes | radio)
     * 
     * @return void
     */
    protected static function setMultiDecorator($format, $element, $type) {
        $decorator = self::$_MultiDecorator[$format];
        $element->setDecorators($decorator);
        $element->setSeparator(PHP_EOL);
        
        $labelClass = $element->getAttrib('label_class');
        if(!$labelClass) {
            $labelClass = array();
        }
        if(!is_array($labelClass)) {
            $labelClass = array($labelClass);
        }
        $labelClass = array_unique(array_merge(array($type), $labelClass));
        $element->setAttrib('label_class', $labelClass);
    } 
}