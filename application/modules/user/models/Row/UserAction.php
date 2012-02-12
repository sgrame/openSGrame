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
     * @return this 
     */
    public function setDateExpire(Zend_Date $date)
    {
        $this->date_expire = $date->get('YYYY-MM-dd HH:mm:ss');
        return $this;
    }
    
    /**
     * Set the userAction flag as used
     * 
     * @param void
     * 
     * @return this
     */
    public function setUsed()
    {
        $this->used = 1;
        return $this;
    }
    
    /**
     * Check if the userAction is already used
     *
     * @param void
     * 
     * @return bool
     */
    public function isUsed() {
        return (bool)$this->used;
    }

    /**
     * Automatically add extra fields on row insert
     * 
     * Fields that will be added:
     * - token
     */
    protected function _insert()
    {
        if(empty($this->uuid)) {
            $this->uuid  = SG_Token::uuid();
        }
    } 
}
