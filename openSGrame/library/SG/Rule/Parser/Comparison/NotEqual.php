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
 * SG_Rule_Parser_Comparison_NotEqual
 *
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Rule_Parser_Comparison_NotEqual extends SG_Rule_Parser_Abstract
{
    /**
     * Parse a equal string
     * 
     * @param string $string
     * @param SG_Rule_Parser_Patterns $patterns
     * 
     * @return array
     * 
     * @throws SG_Rule_Parser_Exception 
     */
    public function parse($string, SG_Rule_Parser_Patterns $patterns) {
        $info = $patterns->match($string);
        
        if (!isset($info['token']) 
            || $info['token'] !== SG_Rule_Parser_Patterns::COMPARISON_NOT_EQUAL
        ) {
            throw new SG_Rule_Parser_Exception('Unable to parse string.');
        }
        

        $parts  = $this->match($string);
        
        $left  = $patterns->parse($parts[0]);
        $right = $patterns->parse($parts[1]);
        
        return new SG_Rule_Comparison_NotEqual($left, $right);
    }
    
    /**
     * Function to split the string in parts
     * 
     * @param string $string
     * 
     * @return array 
     */
    public function match($string) 
    {
        $result = $this->_split($string);
        if (2 !== count($result)) {
            throw new SG_Rule_Parser_Exception('Unable to parse string.');
        }
        
        return $result;
    }
    
    /**
     * Method to split the string
     * 
     * @param string $string
     * 
     * @return array 
     */
    protected function _split($string)
    {
        $parts  = array();
        $part   = NULL;
        $level  = 0; 
        for($i = 0; $i < strlen($string); $i++) {
            $sub = substr($string, $i, 1);
            
            if ($sub === '(') {
                $part .= $sub;
                $level++;
                continue;
            }
            if ($sub === ')') {
                $part .= $sub;
                $level--;
                continue;
            }
            if ($level > 0) {
                $part .= $sub;
                continue;
            }
            if (substr($string, $i, 2) === '!=') {
                $parts[] = $part;
                $part = NULL;
                $i++;
                continue;
            }

            $part .= $sub;
        }
        $parts[] = $part;
        
        return $parts;
    }
}