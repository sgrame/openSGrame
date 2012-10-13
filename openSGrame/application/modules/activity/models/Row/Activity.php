<?php
/**
 * @category Activity
 * @package  Model
 * @author   Peter Decuyper <peter@serial-graphics.be>
 * @filesource
 */


/**
 * Activity_Model_Row_Activity
 *
 * Candidate row
 *
 * @category Activity
 * @package  Model
 * @author   Peter Decuyper <peter@serial-graphics.be>
 */
class Activity_Model_Row_Activity extends Zend_Db_Table_Row_Abstract
{
    /**
     * The table class name
     * 
     * @var string
     */
    protected $_tableClass = 'Activity_Model_DbTable_Activity';
    
    /**
     * The unserialize parameters array
     * 
     * @var array
     */
    protected $_params;
    

    /**
     * Get the params
     * 
     * @param void
     * 
     * @return mixed
     */
    public function getParams()
    {
        if(is_null($this->_params)) {
            $this->_params = (empty($this->params))
                ? array()
                : unserialize($this->params);
        }
        
        return $this->_params;
    }
    
    /**
     * Set the params
     * 
     * @param array
     * 
     * @return Activity_Model_Row_Activity
     */
    public function setParams($params)
    {
        $this->_params = $params;
        return $this;
    }
    
    
    /**
     * Pre insert logic
     */
    protected function _insert()
    {
        $this->_preSave();
    }
    
    /**
     * Pre update logic
     */
    protected function _update()
    {
        $this->_preSave();
    }
    
    /**
     * Shared pre insert/update logic
     */
    protected function _preSave()
    {
        $this->params = serialize($this->getParams());
    }
}

