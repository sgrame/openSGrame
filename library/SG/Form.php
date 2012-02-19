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
 * SG_Form
 *
 * This adds the prefix paths for 
 * - SG_Filter
 * - SG_Validate
 * - SG_Decorator
 *
 * Extends the EasyBib_Form for Twitter Bootstrap integration
 *
 * @category SG
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Form extends TB_Form
{
    /**
     * Optional model stored in the form
     */
    protected $model;

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
             );
             
         /*    
         $this->addElementPrefixPath(
                 'SG_Form_Decorator',
                 'SG/Form/Decorator/',
                 Zend_Form_Element::DECORATOR
             );
         */
        
        if(!isset($_options['attribs']['class'])) {
            //$this->setAttrib('class', 'form-horizontal');
        }

        parent::__construct($_options);
    }
    
    /**
     * @return $model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }
}

