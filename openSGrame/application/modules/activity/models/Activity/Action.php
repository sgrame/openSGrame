<?php
/**
 * @category Activity_Model
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * Activity Action link
 * 
 * This object describes an activity action link.
 * The collected data will be used to create a link using Zend_View_Helper_URL
 * with as action the getUrlOptions() return array.
 *
 * @category Activity_Model
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class Activity_Model_Activity_Action
{
    /**
     * The text to use in the link
     * 
     * @var string
     */
    protected $_text;
    
    /**
     * The url options
     * 
     * @var array
     */
    protected $_urlOptions = array(
        'module'     => null,
        'controller' => null,
        'action'     => null,
    );
    
    /**
     * The router name
     * 
     * @var string
     */
    protected $_routerName;
    
    /**
     * Class constructor
     * 
     * @param 
     * 
     */
    public function __construct(
        $text = NULL, 
        $module = NULL, 
        $controller = NULL, 
        $action = NULL, 
        $params = array(), 
        $routerName = null
    )
    {
        $this->setText($text)
             ->setModule($module)
             ->setController($controller)
             ->setAction($action)
             ->setParams($params)
             ->setRouterName($routerName);
    }
    
    /**
     * Set the text
     * 
     * @param string $text
     * 
     * @return Activity_Model_Activity_Action
     */
    public function setText($text)
    {
        $this->_text = $text;
        return $this;
    }
    
    /**
     * Get the text
     * 
     * @param void
     * 
     * @return string
     */
    public function getText()
    {
        return $this->_text;
    }
    
    /**
     * Set the module
     * 
     * @param string $module
     * 
     * @return Activity_Model_Activity_Action
     */
    public function setModule($module)
    {
        $this->_urlOptions['module'] = $module;
        return $this;
    }
    
    /**
     * Get the module name
     * 
     * @param void
     * 
     * @return string
     */
    public function getModule()
    {
        return $this->_urlOptions['module'];
    }
    
    /**
     * Set the controller
     * 
     * @param string $controller
     * 
     * @return Activity_Model_Activity_Action
     */
    public function setController($controller)
    {
        $this->_urlOptions['controller'] = $controller;
        return $this;
    }
    
    /**
     * Get the action name
     * 
     * @param void
     * 
     * @return string
     */
    public function getController()
    {
        return $this->_urlOptions['controller'];
    }
    
    /**
     * Set the action
     * 
     * @param string $action
     * 
     * @return Activity_Model_Activity_Action
     */
    public function setAction($action)
    {
        $this->_urlOptions['action'] = $action;
        return $this;
    }
    
    /**
     * Get the action name
     * 
     * @param void
     * 
     * @return string
     */
    public function getAction()
    {
        return $this->_urlOptions['action'];
    }
    
    /**
     * Set a single param
     * 
     * @param string $key
     * @param mixed $value
     * 
     * @return Activity_Model_Activity_Action
     */
    public function setParam($key, $value)
    {
        $this->_urlOptions[$key] = $value;
        return $this;
    }
    
    /**
     * Set multiple params
     * 
     * This are the URL parameters. Do not confuse them with the Activity params
     * 
     * @param array $params
     * 
     * @return Activity_Model_Activity_Action
     */
    public function setParams($params)
    {
        foreach($params AS $key => $value) {
            $this->setParam($key, $value);
        }
        return $this;
    }
    
    /**
     * Get the url options array
     * 
     * @param void
     * 
     * @return array
     */
    public function getUrlOptions()
    {
        return $this->_urlOptions;
    }
    
    /**
     * Set the router name
     * 
     * @param string $routerName
     * 
     * @return Activity_Model_Activity_Action
     */
    public function setRouterName($routerName)
    {
        $this->_routerName = $routerName;
        return $this;
    }
    
    /**
     * Get the router name
     * 
     * @param void
     * 
     * @return string
     */
    public function getRouterName()
    {
        return $this->_routerName;
    }
}