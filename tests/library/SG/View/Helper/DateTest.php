<?php
/**
 * @group SG
 */
class SG_View_Helper_DateTest extends SG_Test_PHPUnit_ControllerTestCase
{
    /**
     * Loads the bootstrap
     */
    public function setUp()
    {
        $this->bootstrap = new Zend_Application(
            APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini'
        );
        parent::setUp();
    }   
  
    /**
     * Test direct
     */
    public function testDate()
    {
        $helper = new SG_View_Helper_Date();
        $helper->setView($this->bootstrap->getBootstrap()->getResource('view'));
        
        // test empty values
        $this->assertNull($helper->date(NULL));
        $this->assertNull($helper->date(''));
        
        // test with wrongly formatted dates
        $date = '1/1/2007';
        $this->assertEquals($date, $helper->date($date));
        $date = 'test 123';
        $this->assertEquals($date, $helper->date($date));
        
        // test with given format & date string
        $date = '1974-07-28';
        $format = 'dd.MM.yyyy';
        $this->assertEquals('28.07.1974', $helper->date($date, $format));
        
        // test with date object and given format
        $date = new Zend_Date('1974-07-28', 'yyyy-MM-dd');
        $format = 'dd MM yyyy';
        $this->assertEquals('28 07 1974', $helper->date($date, $format));
    }
    
    /**
     * Test direct
     */
    public function testDateWithPlatformFormat()
    {
        $vars = SG_Variables::getInstance();
        $originalFormat = $vars->get('date_default_format');
      
        $helper = new SG_View_Helper_Date();
        $helper->setView($this->bootstrap->getBootstrap()->getResource('view'));
        
        $dateString = '1974-07-28';
        $dateObject = new Zend_Date($dateString, 'yyyy-MM-dd');
        
        $this->assertEquals($dateObject->get($originalFormat), $helper->date($dateString));
        $this->assertEquals($dateObject->get($originalFormat), $helper->date($dateObject));
        
        $newFormat = 'MM.dd.yyyy';
        $vars->set('date_default_format', 'MM.dd.yyyy');
        $this->assertEquals($dateObject->get($newFormat), $helper->date($dateString));
        $this->assertEquals($dateObject->get($newFormat), $helper->date($dateObject));
        
        if($originalFormat) {
          $vars->set('date_default_format', $originalFormat);
        }
    }
}