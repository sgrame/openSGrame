<?php
/**
 * @group SG
 * @group SG_File
 */
class SG_File_MimeTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test get by extention
     */
    public function testMimeByExtention()
    {
        $extention = 'jpg';
        $mime      = 'image/jpeg';
        
        $this->assertEquals(
            $mime, 
            SG_File_Mime::mimeFromExtention($extention)
        );
        $this->assertEquals(
            $mime, 
            SG_File_Mime::mimeFromExtention(strtoupper($extention))
        );
        
        // non existing
        $this->assertEquals(
            'application/octet-stream', 
            SG_File_Mime::mimeFromExtention('whateverextention')
        );
    }
    
    /**
     * Test by filename 
     */
    public function testMimeByFilename()
    {
        $filename = 'test.image.jpg';
        $mime      = 'image/jpeg';
        
        $this->assertEquals($mime, SG_File_Mime::mimeFromFilename($filename));
    }
}