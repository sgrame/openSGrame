<?php
/**
 * @category SG
 * @package  Filter
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Filter_DateBe
 *
 * Formats a date as DD/MM/YYYY
 *
 * @category SG
 * @package  Filter
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Filter_DateBe extends Zend_Filter_Alnum
{
    /**
     * Pattern for the date split
     * 
     * @var     string
     */
    protected $_patternSplit = "/[,:_\.\/-\s]+/";
    
    /**
     * Glue between date parts
     * 
     * @var     string
     */
    protected $_glue = '/';
    
    /**
     * Constructor
     * 
     * @param     string    parts glue
     */
    public function __construct($_glue = '/')
    {
        $this->_glue = $_glue;
    }
    
    /**
     * Defined by Zend_Filter_Interface
     *
     * Returns the string $value, removing all but alphabetic and digit characters
     *
     * @param  string $value
     * @return string
     */
    public function filter($_value)
    {
        $dateArray = preg_split($this->_patternSplit, $_value);
        if(3 !== count($dateArray)) {
            return $_value;
        }
        
        list($day, $month, $year) = $dateArray;
        
        if ((int)$day < 10) {
            $day = "0".(int)$day;
        }
        if ((int)$month < 10) {
            $month = "0".(int)$month;
        }
            
        return implode($this->_glue, array($day, $month, (int)$year));
    }
}
