<?php
/**
 * @category SG
 * @package  Validate
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Validate_Rule
 *
 * Validates a string to be parsable as a SG_Rule
 *
 * @category SG
 * @package  Validate
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Validate_Rule extends SG_Validate_Abstract
{
    /**
     * Error messages keys
     * 
     * @var string
     */
    const INVALID_RULE    = 'error_not_a_valid_rule';
    
    /**
     * Error messages
     * 
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID_RULE    => "'%value%' is not a valid rule",
    );
    
    /**
     * Parser
     * 
     * @var SG_Rule_Parser_Rule
     */
    protected $_parser;
    
    /**
     * Patterns
     * 
     * @var SG_Rule_Parser_Patterns
     */
    protected $_patterns;
    
    /**
     * Set the parser
     * 
     * @param SG_Rule_Parser
     * 
     * @return SG_Validate_Rule
     */
    public function setParser(SG_Rule_Parser_Rule $parser)
    {
        $this->_parser = $parser;
        return $this;
    }
    
    /**
     * Get the parser
     * 
     * @param void
     * 
     * @return SG_Rule_Parser
     */
    public function getParser()
    {
        return $this->_parser;
    }
    
    /**
     * Set the patterns
     * 
     * @param SG_Rule_Parser_Patterns $patterns
     * 
     * @return SG_Validate_Rule
     */
    public function setPatterns(SG_Rule_Parser_Patterns $patterns)
    {
        $this->_patterns = $patterns;
        return $this;
    }
    
    /**
     * Get the patterns
     * 
     * @param void
     * 
     * @return SG_Rule_Parser_Patterns
     */
    public function getPatterns()
    {
        return $this->_patterns;
    }
     
    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if the given value contains a string that can be parsed by 
     * the SG_Rule_Parser 
     *
     * @param  string $value
     * 
     * @return bool
     */
    public function isValid($value)
    {
        $valueString = (string) $value;
        $this->_setValue($valueString);
        
        $parser = $this->getParser();
        $patterns = $this->getPatterns();
        
        if(empty($parser)) {
            throw new Zend_Validate_Exception("Can't validate rules without parser");
        }
        if(empty($patterns)) {
            throw new Zend_Validate_Exception("Can't validate rules without patterns");
        }
            
        try {
            $parser->parse($value, $patterns);
        }
        catch(Exception $e) {
            $this->_error(self::INVALID_RULE);
            return false;
        }
        
        return true;
    }
}