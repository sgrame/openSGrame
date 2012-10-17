<?php
/**
 * @category Activity_Model
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * Activity model
 * 
 * This provides the business logic to create and load Activities
 *
 * @category Activity_Model
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class Activity_Model_Activity
{
    /**
     * The table mapper
     * 
     * @var Activity_Model_DbTable_Activity
     */
    protected $_mapper;
    
    /**
     * Constructor
     * 
     * @param Zend_Db_Table $mapper
     *     (optional) if non given, Activity_Model_DbTable_Activity will be used
     */
    public function __construct($mapper = NULL)
    {
        if (!$mapper) {
            $mapper = new Activity_Model_DbTable_Activity();
        }
        
        $this->_mapper = $mapper;
    }
        
    /**
     * Find activity by id
     * 
     * @param int $id
     * 
     * @return Activity_Model_Activity_Abstract
     */
    public function findById($id)
    {
        return $this->_mapper->find($id)->current();
    }
    
    /**
     * Find activities, get results as a paginator
     * 
     * @param int $page
     * @param array $order
     *     (optional) order by
     * @param array $search
     *     (optional) search parameters
     *  
     * @return Zend_Paginator
     */
    public function findBySearch($page = 0, $order = array(), $search = array())
    {
        $activities = $this->_mapper->findBySearch($search, $order);
        $paged = Zend_Paginator::factory($activities);
        $paged->setCurrentPageNumber((int)$page);
        return $paged;
    }
    
    
    
    /**
     * Extract the id of an activity
     * 
     * @param int|Activity_Model_Activity_Abstract
     * 
     * @return int
     * @throws Zend_Db_Table_Row_Exception
     */
    public static function extractActivityId($activity)
    {
        if(is_numeric($activity)) {
            return (int)$activity;
        }
        
        if($activity instanceof Activity_Model_Activity_Abstract) {
            return (int)$activity->getRow()->id;
        }
        
        throw new Zend_Db_Table_Row_Exception(
            'No valid activity ID or Activity object'
        );
    }
    
}