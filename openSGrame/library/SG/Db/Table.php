<?php
/**
 * @category SG
 * @package  Db
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Db_Table
 *
 * This extension of the Zend_Db_Table adds "auto fields" to table records:
 *  - owner_id : The owner of the record (default the currently logged in user)
 *  - created  : The date the record was created (first data entry)
 * 
 *  - uuid     : An universal unique identifier. 
 *               Will be auto created using the SG_Token::uuid() method
 * 
 *  - ci       : The user who has written the record (insert/update)
 *  - cd       : The data the record was written (insert/update)
 *  - cr       : Contingency record id
 *               When a record is updated or deleted, a copy of the old row is
 *               inserted in the same table with a link to the original record
 *               id. The cr value refers to the original record. The original 
 *               record has no cr value (NULL).
 *
 * @category SG
 * @package  Db
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Db_Table extends Zend_Db_Table
{
    /**
     * The primary key name is default "id"
     * 
     * @var     string
     */
    protected $_primary = array(1 => 'id');
  
    /**
     * Whether or not this table should use cr records 
     * when updating or deleting
     * 
     * @var     bool
     */
    protected $_contingency = false;
    
    /**
     * Method to know if the cr is on or of
     * 
     * @return     bool
     */
    public function getContingency()
    {
        return (bool)$this->_contingency;
    }
    
    /**
     * Method to set the with cr
     * 
     * @param     bool
     * @return     SG_Db_Table
     */
    public function setContingency($_contingency)
    {
        if(!$this->hasContingencyField())
        {
            throw new Zend_Db_Table_Exception(
                'The table ' . $this->_name . ' has no Contignency Field'
            );
        }
        $this->_contingency = (bool)$_contingency;
        return $this;
    }
    
    /**
     * Method to know if the table has a contingency record (cr) field
     * 
     * @param void
     * 
     * @return bool 
     */
    public function hasContingencyField()
    {
        return array_key_exists('cr', $this->info('metadata'));
    }
    
    /**
     * Method to know if the table has a created record (created) field
     * 
     * @param void
     * 
     * @return bool
     */
    public function hasCreatedField()
    {
        return array_key_exists('created', $this->info('metadata'));
    }
    
    /**
     * Method to know if the table has an owner id (owner_id) field
     * 
     * @param void
     * 
     * @return bool
     */
    public function hasOwnerIdField()
    {
        return array_key_exists('owner_id', $this->info('metadata'));
    }
    
    /**
     * Method to know if the table has an universal unique identifier (uuid) field
     * 
     * @param void
     * 
     * @return bool
     */
    public function hasUuidField()
    {
        return array_key_exists('uuid', $this->info('metadata'));
    }
    
    /**
     * Add extra data to the insert array
     * This adds Creator Id (ci) and Create Date (cd) to the array
     *
     * @param array $data
     *     ColumnName => value pairs.
     * 
     * @return mixed
     *     The primary key of the row inserted.
     */
    public function insert(array $data)
    {
        $data = $this->_addCreatorIdAndCreateDateTime($data);
        $data = $this->_addOwnerIdAndCreated($data);
        $data = $this->_addUuid($data);
        return parent::insert($this->_addCreatorIdAndCreateDateTime($data));
    }
    
    /**
     * Check if we need to create a cr record before we update the record
     * 
     * @param     array    Column-value pairs.
     * @param      array|string $where An SQL WHERE clause, or an array of SQL WHERE clauses
     * @return    int     The number of rows updated.
     */
    public function update(array $_data, $_where)
    { 
        // check if we need to create cr records
        if(!$this->getContingency())
        {   
            return parent::update(
                $this->_addCreatorIdAndCreateDateTime($_data), $_where
            );
        }
        
        // create the cr records
        $newWhere = $this->_createContingencyRecords($_data, $_where, 'update');
        
        // if we dont get a where there are no records that needs to be updated
        if(!(bool)$newWhere)
        {
            return 0;
        }
        
        // update the data with the new where statement
        return parent::update(
            $this->_addCreatorIdAndCreateDateTime($_data), $newWhere
        );
    }
    
    /**
     * Override the delete so contingency records are created on delete
     * Deletes existing rows.
     *
     * @param  array|string $where SQL WHERE clause(s).
     * @return int          The number of rows deleted.
     */
    public function delete($_where)
    {
        // check if we need to create cr records
        if(!$this->getContingency())
        {   
            return parent::delete($_where);
        }
        
        // create the contingency records
        $newWhere = $this->_createContingencyRecords(
            array(), $_where, 'delete'
        );
        
        // if we dont get a where there are no records that needs to be updated
        if(!(bool)$newWhere)
        {
            return 0;
        }
        
        // get the db adapter
        $db = $this->getAdapter();
        
        // delete the records by setting the cr equal to the id values
        $values = $this->_addCreatorIdAndCreateDateTime(array());
        $sql = 'UPDATE ' 
               . $db->quoteTableAs($this->_name) 
               . ' SET cr = id, ci = ?, cd = ' . $values['cd']->__toString()
               . ' WHERE ' . $newWhere
               . ' ;';

        $stmt = $db->query($sql, $values['ci']);
        
        return $stmt->rowCount();          
    }
    
    /**
     * Override the _fetch method so it adds where cr is null to the statements
     * 
     * @param  Zend_Db_Table_Select $select  query options.
     * @return array An array containing the row results in FETCH_ASSOC mode.
     */
    protected function _fetch(Zend_Db_Table_Select $_select)
    {
        if($this->hasContingencyField())
        {
            $_select->where($this->_name . '.cr IS NULL');
        }
        return parent::_fetch($_select);
    }
    
    /**
     * Create Contingency records based on the where
     * 
     * @param     array    Column-value pairs.
     * @param      array|string $where An SQL WHERE clause, or an array of SQL WHERE clauses
     * @param     string    Query type (update, delete)
     * @return     int        the number of cr records created
     */
    protected function _createContingencyRecords(array $_data, $_where, $_type)
    {
        $primaryKeyName = $this->_primary[1];
        
        // create the basic select
        $select = $this->select();
        // add the wheres (if there are some)
        if(!empty($_where))
        {
            if(!is_array($_where))
            $_where = array($_where);
            
            foreach($_where AS $where)
            {
                $select->where($where);
            }
        }
        // update only non contingency records 
        $select->where('cr IS NULL');
        
        // if it as update query check if the data is changed
        if($_type == 'update')
        {
            // add te data changes to the where statement  
            $wheres = array();
            foreach ($_data as $field => $value) 
            {
                if(!is_null($value)) 
                {
                    $wheres[] = $this->getAdapter()
                        ->quoteInto($this->getAdapter()
                        ->quoteIdentifier($field) . ' != ?', $value);
                    $wheres[] = $this->getAdapter()
                        ->quoteIdentifier($field) . ' is null';
                } 
                else 
                {
                    $wheres[] = $this->getAdapter()
                        ->quoteIdentifier($field) . ' is not null';
                }
            }
            $select->where(implode(' OR ', $wheres));
        }
        
        // get the rowset
        $rows = $this->fetchAll($select);
        
        // if no rows, no updates needed
        if(!$rows || !(bool)$rows->count())
        {
            return false;
        }
        
        // 2. Loop through the data, create the contingency records
        $ids = array();
        while($rows->valid())
        {
            $row = $rows->current();
            
            // store the record id for the new where statement
            $ids[] = $this->getAdapter()->quote($row->{$primaryKeyName});
            
            // create a new record containing the current data
            $crData = $row->toArray();
            
            // set the cr value to the primary key value
            $crData['cr'] = $row->{$primaryKeyName};
            
            // remove the primary key value
            unset($crData[$primaryKeyName]);
            
            // insert the data without overwriting the ci & cd
            parent::insert($crData);
            
            $rows->next();
        }
        
        // create the new update where 
        $where = $primaryKeyName . ' IN (' . implode(',', $ids)  . ')';
        
        return $where;
    }

    /**
     * Method to add owner id and created date to the insert data
     * 
     * @param array $data
     *     ColumnName => Value
     * 
     * @return array
     *     extended data array
     */
    protected function _addOwnerIdAndCreated(array $data)
    {
        if($this->hasOwnerIdField() && empty($data['owner_id'])) {
            $data['owner_id'] = $this->_getCurrentUserId();
        }
        if($this->hasCreatedField() && empty($data['created'])) {
            $data['created']  = $this->_getCurrentDateTime();
        }
        
        return $data;
    }
    
    /**
     * Method to add universal unique ID if none is set yet
     * 
     * @param array $data
     *     ColumnName => Value
     * 
     * @return array
     *     extended data array
     */
    protected function _addUuid(array $data)
    {
        if($this->hasUuidField() && empty($data['uuid'])) {
            $token = new SG_Token();
            $data['uuid'] = $token->uuid();
        }

        return $data;
    }
    
    /**
     * Method to add creator id and create datetime to the insert/update data
     * 
     * @param     array    Column-value pairs.
     * @return     array    Column-value pairs.
     */
    protected function _addCreatorIdAndCreateDateTime(array $_data)
    {
        // Set the default Creator ID to the system user
        $_data['ci'] = 0;
        
        // check if there is a logged in user, if so use the user ID
        $auth = Zend_Auth::getInstance();
        if($auth->hasIdentity())
        {
            $_data['ci'] = $auth->getIdentity()->id;
        }
        
        // Create date
        $date = new Zend_Date();
        $_data['cd'] = $this->_getCurrentDateTime();
        
        return $_data;
    }
    
    /**
     * Set the primary key to "id"
     *
     */
    protected function _setupPrimaryKey()
    {
        $this->_primary = 'id';
        parent::_setupPrimaryKey();
    }
    
    /**
     * This will automatically set table name with prefix from config file
     *
     */
     protected function _setupTableName()
     {
         parent::_setupTableName();
         
         $adapterConfig = $this->getAdapter()->getConfig();
         if(isset($adapterConfig['prefix']))
         {
             $this->_name = $adapterConfig['prefix'] . $this->_name;
         }
     }
     
    /**
     * Get the current logged in user id
     * 
     * @param void
     * 
     * @return int
     */
    protected function _getCurrentUserId()
    {
        static $userId;
        
        if(is_null($userId)) {
            $userId = 0;
            $auth = Zend_Auth::getInstance();
            if($auth->hasIdentity()) {
                $userId = (int)$auth->getIdentity()->id;
            }
         }
         
         return $userId;
    }
    
    /**
     * Get the current timestamp
     * 
     * @TODO: make this Database Provider safe?
     * 
     * @param void
     * 
     * @return string
     */
    protected function _getCurrentDateTime()
    {
        $date = new Zend_Date();
        return $date->get('yyyy-MM-dd HH:mm:ss');
    }
}


