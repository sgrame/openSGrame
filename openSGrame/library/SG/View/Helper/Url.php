<?php
/**
 * @category SG
 * @package  View
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_View_Helper_Url
 *
 * Extends the Zend view helper with the support for a destination variable
 *
 * @category SG
 * @package  View
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_View_Helper_Url extends Zend_View_Helper_Url
{
    /**
     * Generates an url given the name of a route.
     *
     * @access public
     *
     * @param array $urlOptions Options passed to the assemble method of the Route object.
     * @param mixed $name The name of a Route to use. If null it will use the current Route
     * @param bool $reset Whether or not to reset the route defaults with those provided
     * @param bool $encode Whether or not to encode the returned url
     * @param array|string $destination
     *     URL encoded string or array
     *     The array needs to be provided like the naviagtion params
     *     @see http://framework.zend.com/manual/1.11/en/zend.navigation.containers.html
     * 
     * @return string Url for the link href attribute.
     */
    public function url(array $urlOptions = array(), $name = null, $reset = false, $encode = true, $destination = null)
    {
        // Set default urlOptions if RESET is triggered
        if ($reset) {
            $this->_urlOptionsDefault($urlOptions, $reset);
        }
        
        // get the URL
        $url = parent::url($urlOptions, $name, $reset, $encode);
      
        // add the destination to it (if neccesary)
        $this->_urlAddDestination($url, $destination);
        
        return $url;
    }
    
    /**
     * Check the language of the requested URL. 
     * If none is provided, use the current language.
     * 
     * @param array $urlOptions
     * 
     * @return void
     */
    protected function _urlOptionsDefault(&$urlOptions) 
    {
        // set the current language if none is given
        if (empty($urlOptions['language'])) {
            $urlOptions['language'] = Zend_Controller_Front::getInstance()
                ->getRequest()
                ->getParam('language');
        }
    }
    
    /**
     * Check if we need to add a destination get param to the URL string.
     * Add it if needed.
     * 
     * @param string $url
     * @param string $destination
     * 
     * @return void 
     */
    protected function _urlAddDestination(&$url, $destination = null) 
    {
        if (empty($destination)) {
            return;
        }
         
        if(is_array($destination)) {
            $route = 'default';
            if(isset($destination['route'])) {
                $route = $destination['route'];
                unset($destination['route']);
            }
                
            $reset = false;
            if(isset($destination['reset_params'])) {
                $reset = $destination['reset_params'];
                unset($destination['reset_params']);
            }
                
            $destination = self::url(
                $destination,
                $route,
                $reset,
                true
            );
        }
            
        $url .= '?destination=' . $destination;
    }
}
