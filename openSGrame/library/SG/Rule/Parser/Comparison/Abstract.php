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
 * SG_Rule_Parser_Comparison_Abstract
 *
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Rule_Parser_Comparison_Abstract extends SG_Rule_Parser_Abstract
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
    protected $_split = NULL;
    
    /**
     * Split exclude
     * 
     * @var array 
     */
    protected $_exclude = array();
    
    
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
        
        $parts = $patterns->split($this->_split, $string, $this->_exclude);
        if (2 !== count($parts)) {
            throw new SG_Rule_Parser_Exception('Unable to parse string.');
        }
        
        $left  = $patterns->parse($parts[0]);
        $right = $patterns->parse($parts[1]);
        
        $className = 'SG_Rule_' . $this->_pattern;
        return new $className($left, $right);
    }
}