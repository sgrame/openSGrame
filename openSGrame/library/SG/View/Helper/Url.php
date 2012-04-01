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
        $url = parent::url($urlOptions, $name, $reset, $encode);
      
        if(!empty($destination)) {
            if(is_array($destination)) {
                $dest_name = 'default';
                if(isset($destination['route'])) {
                    $dest_name = $destination['route'];
                    unset($destination['route']);
                }
                
                $dest_reset = false;
                if(isset($destination['reset_params'])) {
                    $dest_reset = $destination['reset_params'];
                    unset($destination['reset_params']);
                }
                
                $destination = parent::url(
                    $destination,
                    $dest_name,
                    $dest_reset,
                    true
                );
            }
            
            $url .= '?destination=' . $destination;
        }
        
        return $url;
    }
}
