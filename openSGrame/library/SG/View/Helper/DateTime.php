<?php
/**
 * @category SG
 * @package  View_Helper
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_View_Helper_DateTime
 *
 * Formats a given date string (yyyy-MM-dd HH:mm:ss) or Zend_Date object in the 
 * default date format or the given format
 *
 * @category SG
 * @package  View_Helper
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_View_Helper_DateTime extends Zend_View_Helper_Abstract
{
    /**
     * The internal date format
     * 
     * @var string
     */
    protected $_internalFormat = 'yyyy-MM-dd HH:mm:ss';
    
    /**
     * View instance
     *
     * @var Zend_View_Instance
     */
    public $view = null;


    /**
     * Sets the view instance.
     *
     * @param  Zend_View_Interface $view View instance
     * @return Zend_View_Helper_PaginationControl
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }
  
    /**
     * Outputs the given date in the settings default_date_format format 
     * or the given format.
     *
     * @param mixed $date
     *     A Zend_Date object or date string
     * @param string
     *     (optional) The format the date should be rendered.
     *     If none given, the format stored in the variable tabel 
     *     (date_default_format)will be used. 
     * 
     * @return string
     */
    public function dateTime($date, $format = null)
    {
        if(empty($date)) {
            return null;
        }
        
        if(!$format) {
            $format = SG_Variables::getInstance()->get(
                'datetime_default_format', 
                'dd/MM/YYYY HH:mm:ss'
            );
        }
        
        if(!($date instanceof Zend_Date)) {
            // check if date is in the yyyy-MM-dd HH:mm:ss format
            $validator = new Zend_Validate_Date();
            $validator->setFormat($this->_internalFormat);
            if(!$validator->isValid($date)) {
                return $this->view->escape($date);
            }
            
            $date = new Zend_Date($date, $this->_internalFormat);
        }
        
        return $this->view->escape($date->get($format));
    }

}
