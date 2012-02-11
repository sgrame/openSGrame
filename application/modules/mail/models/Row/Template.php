<?php
/**
 * @category Mail_Model_Row
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * Mail_Model_Item_Template
 *
 * Mail template record
 *
 * @category Mail_Model_Row
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class Mail_Model_Row_Template extends Zend_Db_Table_Row_Abstract
{
    /**
     * Unserialised replacements holder
     * 
     * @var    array
     */
    protected $_replacements;
    
    /**
     * The table class name
     * 
     * @var     string
     */
    protected $_tableClass = 'Mail_Model_DbTable_Template';
    
    /**
     * Get the replacement info
     * 
     * @param    void
     * @return    array
     */
    public function getReplacements()
    {
        if(!$this->_replacements)
        {
            $this->_replacements = array();
            
            // split by lines
            $rows = explode(PHP_EOL, $this->replacements);
            
            // go trough the parts
            foreach($rows AS $row)
            {
                $parts = explode('|', $row, 2);
                if(2 === count($parts))
                {
                    $this->_replacements[$parts[0]] = $parts[1];
                }
            }
        }
        
        return $this->_replacements;
    }
    
    /**
     * Set the replacements from a key=>value array
     * 
     * @param    array
     * @return    Mail_Model_Template
     */
    public function setReplacements($_replacements)
    {
        // reset the holder
        $this->_replacements = null;
        
        $data = array();
        
        // create the string
        foreach($_replacements AS $key => $value)
        {
            $data = $key . '|' . $value;
        }
        
        $this->replacements = implode(PHP_EOL, $data);
        
        return $this;
    }
    
    /**
     * Get the subject filled with replacement data
     * 
     * @param    array    data
     * @return    string
     */
    public function getSubjectFilled($_data)
    {
        return $this->_fillString($this->subject, $_data);
    }
    
    /**
     * Get the body filled with replacement data
     * 
     * @param    array    data
     * @return    string
     */
    public function getBodyFilled($_data)
    {
        return $this->_fillString($this->body, $_data);
    }
    
    /**
     * Fill a string with given data
     * 
     * @param    string    The string to fill
     * @param    array    The data to fill the string with
     * @return    string
     */
    protected function _fillString($_string, $_data)
    {
        if(!is_array($_data))
        {
            throw new Zend_Db_Table_Row_Exception(
                'Strings can only be filled with array data.'
            );
        }
        
        $vars = array(
            'pattern' => array(), 
            'replace' => array()
        );
        
        // only valid replacements
        $replacements = $this->getReplacements();
        foreach($_data AS $key => $value)
        {
            if(isset($replacements[$key]))
            {
                $vars['pattern'][] = '/{' . $key . '}/';
                $vars['replace'][] = $value;
            }
        }
        
        // replace the string
        return preg_replace($vars['pattern'], $vars['replace'], $_string);
    }
}
