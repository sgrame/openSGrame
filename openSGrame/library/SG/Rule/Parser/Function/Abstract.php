<?php
/**
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Rule_Parser_Function_Abstract
 *
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Rule_Parser_Function_Abstract extends SG_Rule_Parser_Abstract
{
    /**
     * Expected pattern
     * 
     * @var string 
     */
    protected $_pattern = NULL;
    
    /**
     * Split value
     * 
     * @var string 
     */
    protected $_split = array(',', ';');
    
    /**
     * Prefix
     * 
     * @var array 
     */
    protected $_prefix = NULL;
    
    
    /**
     * Parse a comparison string
     * 
     * @param string $string
     * @param SG_Rule_Parser_Patterns $patterns
     * 
     * @return SG_Rule_Comparison_Abstract
     * 
     * @throws SG_Rule_Parser_Exception 
     */
    public function parse($string, SG_Rule_Parser_Patterns $patterns) {
        $info = $patterns->match($string);
        if (!isset($info['token']) 
            || $info['token'] !== $this->_pattern
        ) {
            throw new SG_Rule_Parser_Exception('Unable to parse string.');
        }
        
        // Remove the function wrapper
        $function_pattern = array(
            '/^' . $this->_prefix . '\(/',
            '/\)$/'
        );
        $content = preg_replace($function_pattern, NULL, $string);
        
        $parts = $patterns->split($this->_split, $content);
        if (0 === count($parts)) {
            throw new SG_Rule_Parser_Exception('Unable to parse string.');
        }
        foreach($parts AS $key => $part) {
            $parts[$key] = $patterns->parse($part);
        }
        
        $className = 'SG_Rule_' . $this->_pattern;
        return new $className($parts);
    }
}