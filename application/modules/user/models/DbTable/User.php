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
     * Get a user by its email
     * 
     * @param string $_email
     * @return  Zend_Db_Table_Rowset
     */
    public function findByEmail($_email)
    {
        $select = $this->select();
        $select->where('email = ?', $_email);
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
     * @param string $username
     * @param mixed $excludeUserId
     *     (optional) User Id to exclude
     * 
     * @return  bool
     */
    public function usernameExists($username, $excludeUserId = null)
    {
        $users = $this->findByUsername($username);
        
        if(!is_null($excludeUserId)) {
            $excludeUserId = (int)$excludeUserId;
        }
        
        foreach($users AS $user) {
            if((int)$user->id === $excludeUserId) {
                continue;
            }
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Check email address exists
     * 
     * @param string $email
     * @param mixed $excludeUserId
     *     (optional) User Id to exclude
     * 
     * @return  bool
     */
    public function emailExists($email, $excludeUserId = null)
    {
        $users = $this->findByEmail($email);
        
        if(!is_null($excludeUserId)) {
            $excludeUserId = (int)$excludeUserId;
        }
        
        foreach($users AS $user) {
            if((int)$user->id === $excludeUserId) {
                continue;
            }
            
            return true;
        }
        
        return false;
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

