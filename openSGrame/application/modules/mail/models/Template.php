<?php
/**
 * @category Mail_Model
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * Mail_Model_Template
 *
 * Mail template model
 *
 * @category Mail_Model
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class Mail_Model_Template extends Zend_Db_Table_Row_Abstract
{
    /**
     * Mapper
     * 
     * @var Mail_Model_DbTable_Template
     */
    protected $_mapper;
    
    /**
     * Constructor
     * 
     * @param Zend_Db_Table_Abstract
     */
    public function __construct($mapper = null) {
        if(!($mapper instanceof Zend_Db_Table_Abstract)) {
            $mapper = new Mail_Model_DbTable_Template();
        }
        
        $this->_mapper = $mapper;
    }
  
    /**
     * Find a template by its realm
     * 
     * @param string $realm
     * @param string $language
     *     (optional) The language of the template
     * 
     * @return Mail_Model_Row_Template
     */
    public function findByRealm($realm, $language = 'en') {
        return $this->_mapper->findByRealm($realm, $language)->current();
    }
}