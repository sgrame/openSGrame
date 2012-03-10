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
 * SG_Form_Element_Note
 *
 * Form element to output plain text instead of input field
 *
 * @category SG
 * @package  Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Form_Element_Note extends Zend_Form_Element_Xhtml 
{ 
    public $helper = 'formNote'; 
    
    /**
     * Init
     */
    public function init()
    {
        $this->setIgnore(true);
    }
}