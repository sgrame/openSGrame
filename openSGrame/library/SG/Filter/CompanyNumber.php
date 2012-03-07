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
 * SG_Filter_CompanyNumber
 *
 * Company number input filter
 * Removes all non numeric values from a Company Number string.
 * It glues the parts by the given glue sting (default ".").
 * 
 * Options:
 *   - allowWhiteSpace: allow whitespaces in the number
 *
 * @category SG
 * @package  Filter
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Filter_CompanyNumber extends Zend_Filter_Alnum
{
    /**
     * Glue between number groups
     * 
     * @var     string
     */
    protected $_glue = '.';
    
    /**
     * Constructor
     * 
     * @param     string    parts glue
     */
    public function __construct($_glue = '.')
    {
        $this->_glue = $_glue;
    }
    
    /**
     * Defined by Zend_Filter_Interface
     *
     * @param  string $value
     * @return string
     */
    public function filter($_value)
    {        
        //strip all non-digit chars
        $value = preg_replace("/[^([:digit:])]/", '', $_value);

        //string should now have 10 chars to be valid
        if (strlen($value) == 10) {
            //set dashes dashes
            $split = array();
            $split[] = substr($value, 0, 4);
            $split[] = substr($value, 4, 3);
            $split[] = substr($value, 7, 3);
            $value = implode($this->_glue, $split);
        }

        return $value;
    }
}