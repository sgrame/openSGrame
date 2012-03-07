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
 * SG_Filter_Url
 *
 * URL input filter
 * Adds missing http:// to an URL
 *
 * @category SG
 * @package  Filter
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Filter_Url extends Zend_Filter_Alnum
{
    /**
     * Pattern to check if the http:// is in front of the string
     */
    const PATTERN = '/^http(s?):\/\//';
    
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
        $url = (string)$_value;
        
        if(isset($url{0}))
        {
            if(!preg_match(self::PATTERN, $url))
            {
                $url = 'http://' . $url;
            }
        }
        
        return $url;
    }
}
