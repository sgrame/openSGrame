<?php 
/**
 * The User Action row object
 */
class User_Model_Row_UserAction extends Zend_Db_Table_Row_Abstract
{
    /**
     * The table class name
     * 
     * @var string
     */
    protected $_tableClass = 'User_Model_DbTable_UserAction';
    
    /**
     * Get the expire date as Zend_Date object
     * Will return NULL if there is no expire date set
     * 
     * @param void
     * 
     * @return Zend_Date
     */
    public function getDateExpire()
    {
        if(empty($this->date_expire)) {
            return null;
        }
        
        $date = new Zend_Date($this->date_expire, 'YYYY-MM-dd HH:mm:ss');
        return $date;
    }
    
    /**
     * Set the expire date
     * 
     * @param Zend_Date
     * 
     * @return self 
     */
    public function setDateExpire(Zend_Date $date)
    {
        $this->date_expire = $date->get('YYYY-MM-dd HH:mm:ss');
        return $this;
    }

    /**
     * Automatically add extra fields on row insert
     * 
     * Fields that will be added:
     * - token
     */
    protected function _insert()
    {
        if(empty($this->token)) {
            $this->token  = SG_Token::uuid();
        }
    } 
}
