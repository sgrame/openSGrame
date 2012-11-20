<?php
/**
 * @category Activity_Model
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * Activity base class
 * 
 * This class is the base of all possible Activity objects.
 * 
 * You need to extend this class if you want to add new records.
 * The class module prefix and name will be used to determen the module & type
 * values of the Activity record.
 * 
 * If your class is: MODULE_Model_Activity_TYPE then
 * - module: MODULE
 * - type  : TYPE
 *
 * You need to implement following methods to support displaying activity:
 * - getTitle()       : Return a translated string that will be used as the title.
 * - getDescription() : Return an array of translated strings. Every array part 
 *                      shall be displayed as an paragraph.
 * - getActions()     : Return an array of Activity_Model_Activity_Action objects.
 *                      These will be used to display a list of action links.
 *
 * @category Activity_Model
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
abstract class Activity_Model_Activity_Abstract 
    implements Activity_Model_Activity_Interface
{
    /**
     * The activity record
     * 
     * @var Activity_Model_Row_Activity
     */
    protected $_activityRow;
    
    /**
     * The user object
     * 
     * @var User_Model_Row_User
     */
    protected $_user;
    
    
    /**
     * Class constructor
     * 
     * @param int|Activity_Model_Row_Activity $activity
     *     (optional) the id or row object of an existing activity
     */
    public function __construct($activity = NULL)
    {
        if ($activity instanceof Activity_Model_Row_Activity) {
            $this->_activityRow = $activity;
        }
        
        // get the Activity Table with the default rowset class
        // the Activity_Model_DbTable_Activity has a special one that returns
        // Activity_Model_Activity_Abstact instances
        $activities = new Activity_Model_DbTable_Activity(array(
            Zend_Db_Table::ROWSET_CLASS => 'Zend_Db_Table_Rowset' 
        ));
        
        // load the activity row by its record ID
        if (is_numeric($activity)) {
            $this->_activityRow = $activities->find($activity)->current();
        }
        
        // create a new activity row
        if (!$this->_activityRow) {
            $this->_activityRow = $activities->createRow(array(
                'module' => $this->_getModuleName(),
                'type'   => $this->_getTypeName(),
            ));
        }
    }
    
    /**
     * Get the activity record
     * 
     * @param void
     * 
     * @return Activity_Model_Row_Activity
     */
    public function getRow()
    {
        return $this->_activityRow;
    }
    
    /**
     * Get the activity parameters
     * 
     * @param void
     * 
     * @return array
     */
    public function getParams() 
    {
        return $this->_activityRow->getParams();
    }
    
    /**
     * Set all the params at once
     * 
     * @param array
     * 
     * @return Activity_Model_Activity_Interace
     */
    public function setParams($params)
    {
        $this->_activityRow->setParams($params);
        return $this;
    }
    
    /**
     * Get a param by its name
     * 
     * @param string $name
     * @param mixed $default
     *     default value in case the parem is not set
     * 
     * @return mixed
     */
    public function getParam($name, $default = NULL) {
        $params = $this->getParams();
        if (!isset($params[$name])) {
            return $default;
        }
        return $params[$name];
    }
    
    /**
     * Add a named parameter
     * 
     * @param string $name
     * @param mixed $value
     * 
     * @return Activity_Model_Activity_Interace
     */
    public function setParam($name, $value) {
        $params = $this->getParams();
        $params[$name] = $value;
        $this->setParams($params);
        return $this;
    }
    
    /**
     * Get the activity title
     * 
     * This will return the translated version (inclusive text replacements) of
     * the title.
     * 
     * @param void
     * 
     * @return string
     */
    public function getTitle()
    {
        return NULL;
    }
    
    /**
     * Get the description
     * 
     * A description will be returned as an array. Every array part will be 
     * dispalyed as a paragraph.
     * 
     * @param void
     * 
     * @return array
     */
    public function getDescription()
    {
        return array();
    }
    
    /**
     * Get the activity actions
     * 
     * This will return an array of Activity_Model_Activity_Action()
     * 
     * @param void
     * 
     * @return array
     */
    public function getActions() {
        return array();
    }
    
    /**
     * Get the user related to the activity
     * 
     * @param void
     * 
     * @return User_Model_Row_User
     */
    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = User_Model_User::load($this->_activityRow->owner_id);
        }
        
        return $this->_user;
    }
    
    /**
     * Set the user related to the activity
     * 
     * @param int|User_Model_Row_User
     * 
     * @return Activity_Model_Activity_Interace
     */
    public function setUser($user) {
        $this->_user = User_Model_User::load($user);
        $this->getRow()->owner_id = $this->getUser()->id;
        return $this;
    }
    
    /**
     * Get the activity date as a Zend_Date object.
     * 
     * @param void
     * 
     * @return Zend_Date
     */
    public function getDate()
    {
        if (!$this->_activityRow->created) {
            return new Zend_Date();
        }
        
        return new Zend_Date($this->_activityRow->created, 'yyyy-MM-dd HH:mm:ss');
    }
    
    /**
     * Save an activity record
     * 
     * @param void
     * 
     * @return Activity_Model_Activity_Abstract
     */
    public function save()
    {
        $this->_activityRow->save();
        return $this;
    }
    
    /**
     * Helper to get the module name of the activity class
     * 
     * @param void
     * 
     * @return string
     */
    protected function _getModuleName()
    {
        $parts = explode('_', get_class($this));
        return array_shift($parts);
    }
    
    /**
     * Helper to get the type name of the activity class
     * 
     * @param void
     * 
     * @return string
     */
    protected function _getTypeName()
    {
        $parts = explode('_', get_class($this));
        return array_pop($parts);
    }
}