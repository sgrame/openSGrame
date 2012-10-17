<?php
/**
 * @category Activity_Model
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * Activity_Model_Activity_Interface
 *
 * Activity interface
 *
 * @category Activity_Model
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
interface Activity_Model_Activity_Interface
{
    /**
     * Get the activity parameters
     * 
     * @param void
     * 
     * @return array
     */
    public function getParams();
    
    /**
     * Set all the params at once
     * 
     * @param array $params
     * 
     * @return Activity_Model_Activity_Interface
     */
    public function setParams($params);
    
    /**
     * Add a named parameter
     * 
     * @param string $name
     * @param mixed $value
     * 
     * @return Activity_Model_Activity_Interace
     */
    public function setParam($name, $value);
    
    /**
     * Get a param by its name
     * 
     * @param string $name
     * @param mixed $default
     *     default value in case the parem is not set
     * 
     * @return mixed
     */
    public function getParam($name, $default = NULL);
    
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
    public function getTitle();
    
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
    public function getDescription();
    
    /**
     * Get the activity actions
     * 
     * This will return an array of Activity_Model_Activity_Action()
     * 
     * @param void
     * 
     * @return array
     */
    public function getActions();
    
    /**
     * Get the user related to the activity
     * 
     * @param void
     * 
     * @return User_Model_Row_User
     */
    public function getUser();
    
    /**
     * Get the activity date as a Zend_Date object.
     * 
     * @param void
     * 
     * @return Zend_Date
     */
    public function getDate();
}