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
 * SG_Rule_Function_Collection
 * 
 * This base class supports a collection of params & functions
 * 
 * @category SG
 * @package  Rule
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
abstract class SG_Rule_Function_Collection extends SG_Rule_Function_Abstract
{
    /**
     * The collection to calculate the average out
     * 
     * @var array
     */
    protected $_collection = array();
  
    /**
     * Constructor
     * 
     * @param array $collection
     *     OPTIONAL
     * 
     * @return SG_Rule_Function_Average
     */
    public function __construct($collection = array())
    {
        $this->setCollection($collection);
    }
  
    /**
     * Set the collection
     * 
     * @param array $collection
     * 
     * @return SG_Rule_Function_Average
     */
    public function setCollection($collection)
    {
        $this->_collection = $collection;
        return $this;
    }
    
    /**
     * get the collection
     * 
     * @param void
     * 
     * @return array
     */
    public function getCollection()
    {
        return $this->_collection;
    }
    
    /**
     * Add item to the collection
     * 
     * @param mixed $item
     * 
     * @return SG_Rule_Function_Average
     */
    public function addItem($item)
    {
        $this->_collection[] = $item;
        return $this;
    }
    
    /**
     * Get an array with the values of the collection items
     * 
     * @param SG_Rule_Variables $variables
     * 
     * @return array
     */
    protected function _getCollectionValues(SG_Rule_Variables $variables)
    {
        $values = array();
        foreach($this->_collection AS $item) {
            $values[] = $this->_getItemValue($item, $variables);
        }
        return $values;
    }
    
    /**
     * Get string representation of the rule 
     * 
     * @return string
     */
    public function __toString()
    {
        $parts = array();
        foreach($this->_collection AS $part) {
            $parts[] = (string)$part;
        }
        
        return $this::FUNCTION_NAME . '(' . IMPLODE(';', $parts) . ')';
    }
}

