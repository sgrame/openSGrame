<?php
/**
 * @category Activity
 * @package  Model
 * @author   Peter Decuyper <peter@serial-graphics.be>
 * @filesource
 */


/**
 * Activity table rowset
 * 
 * This rowset returns objects of the type Activity_Model_Activity_Abstract.
 * It will determen whitch objects to return based on the module & type defined
 * in the Row.
 * 
 * Example:
 * If the module is MyModule and the type is MyType it will return an 
 * MyModule_Model_Activity_MyModule object.
 *
 * @category Activity
 * @package  Model
 * @author   Peter Decuyper <peter@serial-graphics.be>
 */
class Activity_Model_Rowset_Activity extends Zend_Db_Table_Rowset
{
    /**
     * Override the method to get a row.
     * 
     * We use this as a factory to get the specific row objects based on the 
     * report line type
     * 
     * @param int
     * 
     * @return Activity_Model_Activity_Abstract
     */
    protected function _loadAndReturnRow($position)
    {
        // get the row item
        $row = parent::_loadAndReturnRow($position);
        if (!$row) {
            return NULL;
        }
        
        // create the element
        $className = $row->module . '_Model_Activity_' . $row->type;
        
        if (!class_exists($className)) {
            var_dump('Class does not exists', $className); die;
            throw new Zend_Db_Exception(
                'A class of the type "' . $className . '" does not exists'
            );
        }
        
        $activity = new $className($row);
        return $activity;
    }
}

