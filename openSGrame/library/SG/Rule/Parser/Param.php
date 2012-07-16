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
 * SG_Rule_Parser_Param
 *
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Rule_Parser_Param extends SG_Rule_Parser_Abstract
{
    /**
     * Parse a param string
     * 
     * @param  string $source
     * 
     * @return array
     * 
     * @throws SG_Rule_Parser_Exception 
     */
    public function parse($string) {
        $parser = new SG_Rule_Parser_Pattern();
        $info   = $parser->parse($string);
        
        if (!isset($info[0]) 
            || $info[0]['token'] !== SG_Rule_Parser_Pattern::PARAM
        ) {
            throw new SG_Rule_Parser_Exception('Unable to parse string.');
        }
        
        return new SG_Rule_Param($string);
    }
}