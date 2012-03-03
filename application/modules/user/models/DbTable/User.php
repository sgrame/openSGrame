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
     * @param array $search
     * @param string $order
     * @param string $direction
     * 
     * @return  Zend_Db_Table_Rowset
     */
    public function fetchBySearch($search = array(), $order = null, $direction = null)
    {
        $select = $this->select();
        $select->from($this->_name, '*');
        // filter system users
        if(isset($search['excludeSystemUsers']) && $search['excludeSystemUsers']) {
            $select->where($this->_name . '.id > 1');
        }
        
        // search username
        if(isset($search['username']) && 0 < strlen($search['username'])) {
            $select->where(
                $this->_name . '.username LIKE ?',
                str_replace('*', '%', $search['username'])
            );
        }
        // search email
        if(isset($search['email']) && 0 < strlen($search['email'])) {
            $select->where(
                $this->_name . '.email LIKE ?',
                str_replace('*', '%', $search['email'])
            );
        }
        
        // status
        if(isset($search['status']) && 0 < strlen($search['status'])) {
            switch($search['status']) {
                case 'active':
                    $select->where($this->_name . '.blocked = 0');
                    $select->where($this->_name . '.locked = 0');
                    break;
                
                case 'blocked':
                    $select->where($this->_name . '.blocked = 1');
                    break;
                  
                case 'locked':
                    $select->where($this->_name . '.locked = 1');
                    break;
            }
                    
        }
        
        // only users from certain groups (id)
        if(isset($search['groups'])) {
            if($search['groups'] == 'none') {
                $select->joinLeft('user_groups', $this->_name . '.id = user_groups.user_id', array());
                $select->where('user_groups.group_id IS NULL');
            }
            else {
                $groups = $this->_searchToArray($search['groups']);
                $select->join('user_groups', $this->_name . '.id = user_groups.user_id', array());
                $select->where('user_groups.group_id IN (?)', $groups);
            }
            
            $select->where('user_groups.cr IS NULL');
        }
        
        // only users with certain roles (id)
        if(isset($search['roles'])) {
            if($search['roles'] == 'none') {
                $select->joinLeft('user_roles', $this->_name . '.id = user_roles.user_id', array());
                $select->where('user_roles.role_id IS NULL');
            }
            else {
                $roles = $this->_searchToArray($search['roles']);
                $select->join('user_roles', $this->_name . '.id = user_roles.user_id', array());
                $select->where('user_roles.role_id IN (?)', $roles);
            }
            
            $select->where('user_roles.cr IS NULL');
        }
        
        // add group by
        if(isset($search['groups']) || isset($search['roles'])) {
            $select->group($this->_name . '.id');
        }
        
        return $this->fetchAll($select);
    }

    /**
     * Transform a search string to an array
     * 
     * converts a comma seperated string to an array
     * 
     * @param mixed $search
     * 
     * @return array
     */
    protected function _searchToArray($search)
    {
        if(is_array($search)) {
            return $search;
        }
        
        if(!preg_match('/\,/', $search)) {
            return array($search);
        }
        
        $search = explode(',', $search);
        foreach($search AS $key => $value) {
            $search[$key] = trim($value);
        }
        
        return $search;
    }
}

