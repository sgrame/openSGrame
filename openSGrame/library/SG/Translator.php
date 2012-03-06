<?php
/**
 * @category SG
 * @package  Translator
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Translator
 *
 * Wrapper around SG_View_Helper_T to have the same functionality within models
 *
 * @category SG
 * @package  Translator
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Translator
{
    /**
     * Singleton instance
     *
     * Marked only as protected to allow extension of the class. To extend,
     * simply override {@link getInstance()}.
     *
     * @var SG_Variables
     */
    protected static $_instance = null;
  
    /**
     * Translator helper container
     * 
     * @var SG_View_Helper_T
     */
    protected $_translator;
    
    /**
     * Constructor
     *
     * Instantiate using {@link getInstance()}; variables is a singleton
     * object.
     *
     * @return void
     */
    protected function __construct($helper = null)
    {
        if($helper instanceof Zend_View_Helper_Translate) {
            $this->_translator = $helper;
        }
        else {
            $this->_translator = new SG_View_Helper_T(
                Zend_Registry::get('Zend_Translate')
            );
        }
    }

    /**
     * Enforce singleton; disallow cloning
     *
     * @return void
     */
    private function __clone()
    {
    }
    
    /**
     * Singleton instance
     *
     * @param Zend_View_Helper_Translate
     *     (optional) Translate view helper
     * 
     * @return SG_Translator
     */
    public static function getInstance($helper = null)
    {
        if (null === self::$_instance) {
            self::$_instance = new self($helper);
        }

        return self::$_instance;
    }
    
    /**
     * Wrapper around the SG_View_Helper_T::t() method
     * 
     * Translate a message
     * You can give multiple params or an array of params.
     * If you want to output another locale just set it as last single parameter
     * Example 1: translate('%1\$s + %2\$s', $value1, $value2, $locale);
     * Example 2: translate('%1\$s + %2\$s', array($value1, $value2), $locale);
     *
     * @param  string $messageid 
     *     Id of the message to be translated
     * 
     * @return string 
     *     Translated message
     */
    public function t($messageId = null)
    {
        $args = func_get_args();
        
        //remove first argument ($messageId)
        array_shift($args);
        
        return $this->_translator->translate($messageId, $args);
    }
}
