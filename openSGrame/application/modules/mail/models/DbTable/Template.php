<?php
/**
 * @category Mail_Model_DbTable
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * Mail_Model_DbTable_Template
 *
 * Mail template mapper
 *
 * @category Mail_Model_Row
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class Mail_Model_DbTable_Template extends SG_Db_Table
{
    /**
     * Table name
     */
    protected $_name = 'mail_template';
    
    /**
     * Row class
     */
    protected $_rowClass = 'Mail_Model_Row_Template';
    
    /**
     * Whether or not this table should use cr records 
     * when updating or deleting
     * 
     * @var     bool
     */
    protected $_contingency = true;
    
    /**
     * Find by the realm
     * 
     * @param    realm
     * @return    Zend_Db_Table_Rowset
     */
    public function findByRealm($realm, $language = 'en')
    {
        $select = $this->select();
        $select->where('realm = ?',    $realm);
        $select->where('language = ?', $language);
        
        return $this->fetchAll($select);
    }
}
