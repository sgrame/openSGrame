<?php
/**
 * @group SG_Modules
 * @group SG_Modules_File
 */
class File_Model_FileTest extends SG_Test_PHPUnit_ControllerTestCase
{
    /**
     * Test file name
     * 
     * @var string 
     */
    const TESTFILE = 'File_Model_FileTest_TestFile.txt';
    
    
    public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
        
        $this->_createTestFile();
    }
    
    public function tearDown()
    {
        $this->_deleteTestFile();
    }
    
    public function testInit()
    {
        $filePath = $this->_getTestFilePath();
        
        // init from file
        $file = new File_Model_File(self::TESTFILE);
        $this->assertNull($file->getId());
        $this->assertEquals(self::TESTFILE, $file->getName());
        $this->assertEquals($filePath, $file->getPath());
        $this->assertEquals('txt', $file->getExtention());
        $this->assertEquals(self::TESTFILE, $file->getUri());
        $this->assertEquals(filesize($filePath), $file->getSize());
        $this->assertEquals('text/plain', $file->getMime());
        $this->assertFalse($file->isPermanent());
        $this->assertTrue($file->isTemporary());
        
        // save to DB
        $id = $file->save();
        $this->assertTrue(0 < $id);
        
        $fileDb = new File_Model_File($id);
        $this->assertEquals($id, $fileDb->getId());
    }
    
    /**
     * Test the rename method 
     */
    public function testRename()
    {
        $newName = 'File_Model_FileTest_TestFile_Renamed.txt';
        
        $file = new File_Model_File(self::TESTFILE);
        $file->save();
        
        $this->assertEquals(self::TESTFILE, $file->getName());
        $this->assertInstanceOf('File_Model_File', $file->rename($newName));
        $this->assertEquals($newName, $file->getName());
    }
    
    /**
     * Test the move method 
     */
    public function testMove()
    {
        $newUri = 'File_Model_FileTest_TestFile_Moved.txt';
        
        $file = new File_Model_File(self::TESTFILE);
        $file->save();

        $this->assertEquals(self::TESTFILE, $file->getUri());
        $this->assertInstanceOf('File_Model_File', $file->move($newUri));
        $this->assertEquals($newUri, $file->getUri());
        
        // move back to let cleanup do its work ;-)
        $file->move(self::TESTFILE);
    }
    
    /**
     * Test the delete method 
     */
    public function testDelete()
    {
        $file = new File_Model_File(self::TESTFILE);
        $id = $file->save();
        
        $this->assertTrue($file->delete());
    }
    
    /**
     * Test the prepare function 
     */
    public function testPrepare()
    {
        // file does not exists
        $uri = self::TESTFILE . '_notexists.txt';
        $file = File_Model_File::prepare($uri);
        $this->assertInstanceOf('File_Model_File', $file);
        $this->assertEquals($uri, $file->getUri());
        $file->delete();
        
        // file already exists
        $uri = self::TESTFILE;
        $file = File_Model_File::prepare($uri);
        $this->assertInstanceOf('File_Model_File', $file);
        $this->assertEquals('File_Model_FileTest_TestFile_0.txt', $file->getUri());
        $file->delete();
    }
    
    /**
     * Test the updateSize function 
     */
    public function testUpdateSize()
    {
        $file = new File_Model_File(self::TESTFILE);
        $file->save();
        
        // append extra data to the file
        ob_start();
            readfile($file->getPath());
            $content = ob_get_contents();
        ob_end_clean();
        $handle = fopen($file->getPath(), 'a');
            fwrite($handle, $content);
            fclose($handle);
        
        // test the new filesize
        $this->assertEquals(
            filesize($file->getPath()), 
            $file->updateSize()->getSize()
        );
    }
    
    /**
     * Helper to create the test file
     * 
     * @param void
     * 
     * @return void 
     */
    protected function _createTestFile()
    {
        $filePath = $this->_getTestFilePath();
        if (!touch($filePath)) {
            $this->fail('Can\'t create test file');
        }
        // add some content to the file
        $string = 'abcdefghijklmnopqrestuvwxyz';
        $string .= strtoupper($string);
        $string .= $string;
        $string .= PHP_EOL;
        
        if (!($handle = fopen($filePath, 'w'))) {
            $this->fail('Can\'t create test file');
        }

        for ($i = 0; $i < 100; $i++) {
            if (!fwrite($handle, $string)) {
                $this->fail('Can\'t create test file');
            }
        }
        
        if (!fclose($handle)) {
            $this->fail('Can\'t create test file');
        }
    }
    
    /**
     * Helper to delete the test file
     * 
     * @param void
     * 
     * @return void 
     */
    protected function _deleteTestFile()
    {
        $filePath = $this->_getTestFilePath();
        if (!file_exists($filePath)) {
            return;
        }
        
        unlink($filePath);
        
        $mapper = new File_Model_DbTable_File();
        $file = $mapper->findByUri(self::TESTFILE)->current();
        if ($file) {
            $file->delete();
        }
    }
    
    /**
     * Helper to get the test file path 
     * 
     * @param void
     * 
     * @return string
     */
    protected function _getTestFilePath()
    {
        return APPLICATION_PATH . '/../data/files/' . self::TESTFILE;
    }
}
