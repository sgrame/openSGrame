<?php
/**
 * @category File_Model
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * File_Model_File
 *
 * File model
 *
 * @category File_Model
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class File_Model_File extends Zend_Db_Table_Row_Abstract
{
    /**
     * Mapper
     * 
     * @var File_Model_DbTable_File
     */
    protected $_mapper;
    
    /**
     * The record
     * @var File_Model_Row_File 
     */
    protected $_row;
    
    
    /**
     * Constructor
     * 
     * @param int|string $file
     *     The file record id or file uri 
     *     The object will try to load itself by id or initiate itself by 
     *     the given file path.
     *     The file path must be relative to the openSGrame/data directory
     * @param Zend_Db_Table_Abstract
     *    (optional) the DB mapper to use
     */
    public function __construct($file, $mapper = null) {
        if(!($mapper instanceof Zend_Db_Table_Abstract)) {
            $mapper = new File_Model_DbTable_File();
        }
        $this->_mapper = $mapper;
        
        if (is_numeric($file)) {
            $this->_initFromDb($file);
        }
        else {
            $this->_initFromUri($file);
        }
    }
    
    /**
     * Initiate the file from a record id
     * 
     * @param int $id
     * 
     * @return void 
     */
    protected function _initFromDb($id)
    {
        $this->_row = $this->_mapper->find((int)$id)->current();
    }
    
    /**
     * Initiate the file from a file path (uri)
     * 
     * The uri needs to be relative from the data path 
     * (default openSGrame/data/files)
     * 
     * @param string $uri
     * 
     * @return void
     */
    protected function _initFromUri($uri)
    {
        $filePath = self::createPath($uri);
        if (!file_exists($filePath)) {
            throw new Exception('File does not exist (' . $filePath . ')');
        }
        
        // extract meta data
        $size = filesize($filePath);
        $name = basename($filePath);
        $mime = SG_File_Mime::mimeFromFilename($name);
        
        $this->_row = $this->_mapper->createRow(array(
            'filename' => $name,
            'uri'      => $uri,
            'filemime' => $mime,
            'filesize' => $size,
            'status'   => 0,
        ));
    }
    
    /**
     * Get the record id
     * 
     * @param void
     * 
     * @return int 
     */
    public function getId()
    {
        return $this->_row->id;
    }
    
    /**
     * File name
     * 
     * @param void
     * 
     * @return string 
     */
    public function getName()
    {
        return $this->_row->filename;
    }
    
    /**
     * Returns the File extension.
     *
     * @param void
     * 
     * @return string
     *     The File extension
     */
    public function getExtention()
    {
        $ext   = '';
        $parts = explode('.', $this->getName());
        if (count($parts) > 1) {
            $ext = array_pop($parts);
        }
        else {
            $ext = '';
        }
        return $ext;
    }
    
    /**
     *  the uri
     * 
     * @param void
     * 
     * @return sring 
     */
    public function getUri()
    {
        return $this->_row->uri;
    }
    
    /**
     *  the full file path
     * 
     * @param void
     * 
     * @return string 
     */
    public function getPath()
    {
        return self::createPath($this->getUri());
    }
    
    /**
     * File properties
     * 
     * @param void
     * 
     * @return int 
     */
    public function getSize()
    {
        return $this->_row->filesize;
    }
    
    /**
     * Update size based on the file.
     * 
     * Use this to update the size of the file after manipulation.
     * The filesize is taken from the actual file.
     * The new value it automatically saved in the DB.
     * 
     * @param void
     * 
     * @return File_Model_File
     */
    public function updateSize()
    {
        $size = ($this->exists())
            ? filesize($this->getPath())
            : 0;
        $this->_row->filesize = $size;
        $this->save();
        
        return $this;
    }
    
    /**
     * File mime type
     * 
     * @param void
     * 
     * @return string 
     */
    public function getMime()
    {
        return $this->_row->filemime;
    }
    
    /**
     * Make the file permanent
     * 
     * @param void
     * 
     * @return File_Model_File 
     */
    public function setPermanent()
    {
        $this->_row->status = 1;
        return $this;
    }
    
    /**
     * Check if file is permanent
     * 
     * @param void
     * 
     * @return bool 
     */
    public function isPermanent()
    {
        $status = (int)$this->_row->status;
        return (bool)$status;
    }
    
    /**
     * Make the file temporary
     * 
     * @param void
     * 
     * @return File_Model_File 
     */
    public function setTemporary()
    {
        $this->_row->status = 0;
        return $this;
    }
    
    /**
     * Is temporary
     * 
     * @param void
     * 
     * @return bool 
     */
    public function isTemporary()
    {
        return !$this->isPermanent();
    }
    
    /**
     * Check if the file exists in the file system
     * 
     * @param void
     * 
     * @return bool 
     */
    public function exists()
    {
        return file_exists($this->getPath());
    }
    
    /**
     * Rename a file
     * 
     * @param string $name
     *     New name (@todo without extention?)
     * 
     * @return bool
     *     success 
     */
    public function rename($name)
    {
        $this->_row->filename = $name;
        $this->save();
        return $this;
    }
    
    /**
     * Move a file
     * 
     * @param string $uri
     *     The new uri of the file (inclusive the filename)
     * 
     * @return bool
     *     Success 
     */
    public function move($uri)
    {
        $oldPath = $this->getPath();
        $newPath = self::createPath($uri);
        
        if ($this->exists()) {
            if (!rename($oldPath, $newPath)) {
                return false;
            }
        }
        
        $this->_row->uri = $uri;
        $this->save();
        return $this;
    }
    
    /**
     * Delete a file
     * 
     * @param void
     * 
     * @return bool
     *     success 
     */
    public function delete()
    {
        // delete from file system
        if ($this->exists()) {
            if (!unlink($this->getPath())) {
                return false;
            }
        }
        
        // delete from DB
        if (!$this->_row->delete()) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Change the file permissions
     * 
     * @param string $mode
     *     (optional) Set default to 0775
     * 
     * @return bool
     *     success 
     */
    public function setPermissions($mode = 0775)
    {
        $result = false;
        
        if (!$this->exists()) {
            return false;
        }
        
        if (chmod($this->getPath(), (int)$mode)) {
            $result = true;
        }
        return $result;
    }
    
    /**
     * Download a file
     * 
     * This will send by default the file mime headers.
     * You can force the download. This will send headers to the browser so it 
     * auto asks for save (instead of opening it with the default application).
     * 
     * @param bool $force
     *     (optional) Force the download
     * 
     * @return void 
     */
    public function download($force = false)
    {
        if (!$this->exists()) {
            throw new Exception('File does not exist in the file system.');
        }
        
        header('Content-Description: File Transfer');
        if ($force) {
            header('Content-Type: application/octet-stream');
        }
        else {
            header('Content-Type: ' . $this->getMime());
        }
        header('Content-Disposition: attachment; filename=' . $this->getName());
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $this->getSize());
        
        if ($this->getSize() > 1024*1024) {
            $this->_readfileChunked($this->getPath());
        }
        else {
            readfile($this->getPath());
        }
        
        return;
    }
    
    /**
     * Save the file
     * 
     * This will change the changes to the file record to the DB
     * 
     * @param void
     * 
     * @return bool
     *     success 
     */
    public function save() {
        return $this->_row->save();
    }
    
    /**
     * Static function to prepare a file path
     * 
     * This creates a unique file path before a file is stored there.
     * This to avoid trying writing 2 files with the same name on the same 
     * location.
     * 
     * @param string $uri
     *     Uri where the file should be stored
     * 
     * @return File_Model_File 
     */
    static function prepare($uri)
    {
        // mapper
        $mapper = new File_Model_DbTable_File();
        
        // filename
        $filename = basename($uri);
        
        // check if the file exists on the file system, if so suggest other name
        $testuri   = $uri;
        $path      = $uri;
        $extention = null;
        if (preg_match('/(.+)(\.[A-Za-z0-9]+)$/', $uri, $matches)
            && 3 === count($matches)
        ) {
            $path      = $matches[1];
            $extention = $matches[2];
        }
        
        // try to suggest a new name with numeric suffix
        for ($i = 0; $i < 100; $i++) {
            if (!file_exists(self::createPath($testuri)) 
                && !$mapper->findByUri($testuri)->current()
            ) {
                break;
            }
            
            $testuri = $path . '_' . $i . $extention;
        }
        // check if we could create a unique file location
        if (file_exists(self::createPath($testuri))) {
            throw new Exception('Could not create a unique file path for file');
        }
        
        // create the object
        $row = $mapper->createRow(array(
            'filename' => $filename,
            'uri'      => $testuri,
            'filemime' => SG_File_Mime::mimeFromFilename($filename),
            'filesize' => 0,
            'status'   => 0,
        ));
        $row->save();
        
        $file = new File_Model_File($row->id);
        return $file;
    }
    
    
    /**
     * Create a full file path
     * 
     * @param string $uri
     *     The relative uri to a file
     * 
     * @return string 
     */
    static function createPath($uri)
    {
        return self::getBasePath() . $uri;
    }
  
    /**
     *  the file base path
     * 
     * @todo change the file path trough config?
     * 
     * @return string 
     */
    static function getBasePath()
    {
        $path = APPLICATION_PATH . '/../data/files/';
        return $path;
    }
    
    /**
     * Helper method to make the download of large files possible without 
     * using to much memory.
     * 
     * Found this on 
     * @link http://be.php.net/manual/en/function.readfile.php#54295
     * 
     * @param str $filePath
     *     path to file to read
     * @param bool $retbytes
     *     (optional) should this return the number of bytes read
     * 
     * @return mixed
     */
    function _readfileChunked($filePath, $retbytes = true)
    {
        // how many bytes per chunk
        $chunksize = 1 * (1024 * 1024);
        $buffer    = '';
        $cnt       = 0;

        $handle = fopen($filePath, 'rb');
        if ($handle === false) {
            return false;
        }
        while (!feof($handle)) {
            $buffer = fread($handle, $chunksize);
            echo $buffer;
            @ob_flush();
            flush();

            if ($retbytes) {
                $cnt += strlen($buffer);
            }
        }
        $status = fclose($handle);
        if ($retbytes && $status) {
            // return num. bytes delivered like readfile() does.
            return $cnt;
        }
        return $status;
    }
}