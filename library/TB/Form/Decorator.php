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
                    'class' => 'control-label',
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
                'Description',
                array(
                    'tag'   => 'span',
                    'class' => 'help-inline',
                    'style' => 'color: #BFBFBF;'
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
                    'style' => 'color: #404040;'
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
                'Description',
                array(
                    'tag'   => 'span',
                    'class' => 'help-inline',
                    'style' => 'color: #BFBFBF;'
                )
            ),
            array(
                'BootstrapErrors'
            ),
            array(
                'BootstrapTag',
                array(
                    'class' => 'input'
                )
            ),
            array(
                'Label',
                array(
                    'style' => 'color: #404040;'
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
                'ViewHelper'
            ),
            array(
                'BootstrapErrors'
            ),
            array(
                'Description',
                array(
                    'tag'   => 'span',
                    'class' => 'help-blocks',
                    'style' => 'color: #BFBFBF;'
                )
            ),
            /*array(
                array(
                    'listelement' => 'HtmlTag'
                ),
                array(
                    //'tag'   => 'li',
                )
            ),
            array(
                array(
                    'list' => 'HtmlTag'
                ),
                array(
                    'tag'   => 'ul',
                    'class' => 'inputs-list'
                )
            ),*/
            array(
                'BootstrapTag',
                array(
                    'class' => 'input'
                )
            ),
            array(
                'Label',
                array(
                    'style' => 'color: #404040;'
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
        }

        // set form element decorators
        $form->setElementDecorators(self::$_ElementDecorator[$format]);

        // set submit button decorators
        if ($form->getElement($submit_str)) {
            $form->getElement($submit_str)->setDecorators(self::$_SubmitDecorator[$format]);
            if ($format == self::BOOTSTRAP) {
                $attribs = $form->getElement($submit_str)->getAttrib('class');
                if (empty($attribs)) {
                    $attribs = array('btn', 'btn-primary');
                } else {
                    if (is_string($attribs)) {
                        $attribs = array($attribs);
                    }
                    $attribs = array_unique(array_merge(array('btn'), $attribs));
                }
                $form->getElement($submit_str)
                    ->setAttrib('class', $attribs)
                    ->setAttrib('type', 'submit');
                if ($form->getElement($cancel_str)) {
                    $form->getElement($submit_str)->getDecorator('HtmlTag')
                        ->setOption('openOnly', true);
                }
            }
            if ($format == self::TABLE) {
                if ($form->getElement($cancel_str)) {
                    $form->getElement($submit_str)->getDecorator('data')
                        ->setOption('openOnly', true);
                    $form->getElement($submit_str)->getDecorator('row')
                        ->setOption('openOnly', true);
                }
            }
        }

        // set cancel button decorators
        if ($form->getElement($cancel_str)) {
            $form->getElement($cancel_str)->setDecorators(self::$_ResetDecorator[$format]);
            if ($format == self::BOOTSTRAP) {
                $attribs = $form->getElement($cancel_str)->getAttrib('class');
                if (empty($attribs)) {
                    $attribs = array('btn');
                } else {
                    if (is_string($attribs)) {
                        $attribs = array($attribs);
                    }
                    $attribs = array_unique(array_merge(array('btn'), $attribs));
                }
                $form->getElement($cancel_str)
                    ->setAttrib('class', $attribs)
                    ->setAttrib('type', 'reset');
                if ($form->getElement($submit_str)) {
                    $form->getElement($cancel_str)->getDecorator('HtmlTag')
                        ->setOption('closeOnly', true);
                }
            }
            if ($format == self::TABLE) {
                if ($form->getElement($submit_str)) {
                    $form->getElement($cancel_str)->getDecorator('data')
                        ->setOption('closeOnly', true);
                    $form->getElement($cancel_str)->getDecorator('row')
                        ->setOption('closeOnly', true);
                }
            }
        }

        // set hidden, cpatcha, multi input decorators
        foreach ($form->getElements() as $e) {
            if ($e->getType() == 'Zend_Form_Element_Hidden') {
                $e->setDecorators(self::$_HiddenDecorator[$format]);
            }
            if ($e->getType() == 'Zend_Form_Element_Captcha') {
                $e->setDecorators(self::$_CaptchaDecorator[$format]);
            }
            if ($e->getType() == 'Zend_Form_Element_MultiCheckbox') {
                $e->setDecorators(self::$_MultiDecorator[$format]);
                //$e->setSeparator('</li><li>');
                //$e->setAttrib("escape", false);
            }
            if ($e->getType() == 'Zend_Form_Element_Radio') {
                $e->setDecorators(self::$_MultiDecorator[$format]);
                //$e->setSeparator('</li><li>');
            }
        }
    }
}