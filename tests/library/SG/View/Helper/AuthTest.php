<?php
/**
 * @group SG
 */
class SG_View_Helper_AuthTest extends PHPUnit_Framework_TestCase
{
   /**
     * Check if we get the Auth object
     */
    public function testAuth()
    {
        $helper = new SG_View_Helper_Auth();
        
        $this->assertInstanceOf('Zend_Auth', $helper->auth());
    }
}