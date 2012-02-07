<?php
/**
 * Mapper to the User table
 */
class User_Model_DbTable_User extends SG_Db_Table
{
    /**
     * Table name
     * 
     * @var string
     */
    protected $_name = 'user';

    /**
     * The row class name
     * 
     * @var string
     */
    protected $_rowClass = 'User_Model_Row_User';
    
    /**
     * CR on
     * 
     * @var   bool
     */
    protected $_contingency = true;
    
    /**
     * Get a user by its username
     * 
     * @param string
     * @return  Zend_Db_Table_Rowset
     */
    public function findByUsername($_username)
    {
        $select = $this->select();
        $select->where('username = ?', $_username);
        return $this->fetchAll($select);
    }
    
    /**
     * Get a user by his primary keys (id or username)
     * 
     * @param string | id
     * @return  Zend_Db_table_Rowset
     */
    public function findByKey($_key)
    {
        if(is_numeric($_key)) {
            return $this->find((int)$_key);
        }
        
        return $this->findByUsername($_key);
    }
    
    /**
     * Get a user by its username and password
     * 
     * @param string  username
     * @param string  password
     * @return  Zend_Db_Table_Rowset
     */
    public function findByUsernameAndPassword($_username, $_password)
    {
        $select = $this->select();
        $select ->where('username = ?', $_username)
            ->where('password = MD5(CONCAT(?, password_salt))', $_password)
            ->where('locked = 0')
            ->where('blocked = 0');
        return $this->fetchAll($select);
    }
    
    
    /**
     * Check username exists
     * 
     * @param string  username
     * @return  bool
     */
    public function usernameExists($_username)
    {
        $user = $this->findByUsername($_username)->current();
        return (bool)$user;
    }
    
    /**
     * Check if username already exists before insert
     *
     * @param   array Column-value pairs.
     * @return  mixed The primary key of the row inserted.
     */
    public function insert(array $_data)
    {
        $this->_checkUsernameExists($_data);
        return parent::insert($_data);
    }
      
    /**
     * Check if the username already exists for an other user before insert
     * 
     * @param   array Column-value pairs.
     * @param   array|string $where An SQL WHERE clause, or an array of SQL WHERE clauses
     * @return  int     The number of rows updated.
     */
    public function update(array $_data, $_where)
    { 
        $this->_checkUsernameExists($_data);
        return parent::update($_data, $_where);
    }
      
    /**
     * Method to check if the username already is used for other user
     * 
     * @param array
     * @return  void
     * @throws  Zend_DB_Table_Exception
     */
    protected function _checkUsernameExists($_data)
    {
        if(!isset($_data['username'])) {
          return;
        }
        
        $existing = $this->findByUsername($_data['username'])->current();
        
        if(!$existing) {
            return false;
        }
        
        if(!isset($_data['id']) || is_null($_data['id'])) {
            throw new Zend_Db_Table_Exception(
              'Username already exists for other user'
            );
        }
  
        if(intval($existing->id) !== intval($_data['id'])) {
            throw new Zend_Db_Table_Exception(
              'Username already exists for other user'
            );
        }
    }
  
    /**
     * Fetch all by search
     * 
     * @param array
     * @return  Zend_Db_Table_Rowset
     */
    public function fetchBySearch($_search = array())
    {
        $defaultSearch = array(
            'excludeSystemUsers'  => true,
        );
        $search = array_merge($defaultSearch, $_search);
        
        $select = $this->select();
        // filter system users
        if(isset($search['excludeSystemUsers']) && $search['excludeSystemUsers']) {
            $select->where('id > 1');
        }
        
        // search username
        if(isset($search['username']) && 0 < strlen($search['username'])) {
            $select->where(
                'username LIKE ?',
                str_replace('*', '%', $search['username'])
            );
        }
        // search email
        if(isset($search['email']) && 0 < strlen($search['email'])) {
            $select->where(
                'email LIKE ?',
                str_replace('*', '%', $search['email'])
            );
        }
        
        return $this->fetchAll($select);
    }

}

