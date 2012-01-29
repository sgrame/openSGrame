<?php
/**
 * Extended version of the Zend Form
 *
 * @filesource
 * @copyright       Serial Graphics Copyright 2008
 * @author          Serial Graphics <info@serial-graphics.be>
 * @link            http://www.serial-graphics.be
 * @since           Jun 19, 2009
 * @package         SG
 * @subpackage      Form
 */

/**
 * Default SG form class
 * 
 * This adds the prefix paths for 
 * - SG_Filter
 * - SG_Validate
 * - SG_Decorator
 *
 * Extends the EasyBib_Form for Twitter Bootstrap integration
 */
class SG_Form extends EasyBib_Form
{
    /**
     * Constructor
     *
     * Registers form view helper as decorator
     * 
     * @param mixed $options 
     * @return void
     */
    public function __construct($_options = null)
    {
        $this->setMethod('post')
             ->addPrefixPath(
                 'SG_Form_Element', 
                 'SG/Form/Element', 
                 'element'
             )
             ->addElementPrefixPath(
                 'SG_Filter', 
                 'SG/Filter/', 
                 Zend_Form_Element::FILTER
             )
             ->addElementPrefixPath(
                 'SG_Validate', 
                 'SG/Validate/', 
                 Zend_Form_Element::VALIDATE
             )
             ->addElementPrefixPath(
                 'SG_Form_Decorator',
                 'SG/Form/Decorator/',
                 Zend_Form_Element::DECORATOR
             );

        parent::__construct($_options);
    }
}

