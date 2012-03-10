<?php
/**
 * Test the Date form element
 * 
 * @group SG
 */
class SG_Form_Element_DateTest extends SG_Test_PHPUnit_ControllerTestCase
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
        
        $this->_revertDefaultDateFormat();
    }
    
    /**
     * Tear down
     */
    public function tearDown()
    {
        $this->_revertDefaultDateFormat();
    }
    
  
    /**
     * Test the default helper
     */
    public function testHelper()
    {
        $date = new SG_Form_Element_Date('date');
        $this->assertEquals('formDate', $date->helper);
    }
    
    /**
     * Test the default display format
     */
    public function testDisplayFormat()
    {
        $vars = SG_Variables::getInstance();
        
        // check if no variable exists
        $vars->remove('date_default_format');
        $date = new SG_Form_Element_Date('date');
        $this->assertEquals(Zend_Date::DATE_MEDIUM, $date->getDisplayFormat());
        
        // check with variable set
        $format = 'dd/yyyy.MM';
        $vars->set('date_default_format', $format);
        $date = new SG_Form_Element_Date('date');
        $this->assertEquals($format, $date->getDisplayFormat());
        
        $newFormat = 'dd/mm/yyyy';
        $date = new SG_Form_Element_Date('date');
        $this->assertInstanceOf('SG_Form_Element_Date', $date->setDisplayFormat($newFormat));
        $this->assertEquals($newFormat, $date->getDisplayFormat());
    }
    
    /**
     * Test the set/get value methods
     */
    public function testGetSetValue()
    {
        $date = new SG_Form_Element_Date('date');
        
        $this->assertInstanceOf('SG_Form_Element_Date', $date->setValue(NULL));
        $this->assertNull($date->getValue());
        
        $dateString = '1974-07-28';
        $this->assertInstanceOf('SG_Form_Element_Date', $date->setValue($dateString));
        $this->assertEquals($dateString, $date->getValue());
        
        $dateObject = new Zend_Date($dateString, 'yyyy-MM-dd');
        $this->assertInstanceOf('SG_Form_Element_Date', $date->setValue($dateObject));
        $this->assertEquals($dateString, $date->getValue());
        
        $dateFalse = '28-07-1974';
        $this->assertInstanceOf('SG_Form_Element_Date', $date->setValue($dateFalse));
        $this->assertEquals($dateFalse, $date->getValue());
    }
    
    /**
     * Test the getDate() method 
     */
    public function testGetDate()
    {
        $date = new SG_Form_Element_Date('date');
        $dateString = '1974-07-28';
        $dateObject = new Zend_Date($dateString, 'yyyy-MM-dd');
        
        $date->setValue(null);
        $this->assertNull($date->getDate());
        
        $date->setValue($dateString);
        $dateValue = $date->getDate();
        $this->assertInstanceOf('Zend_Date', $dateValue);
        $this->assertEquals($dateString, $dateValue->get('yyyy-MM-dd'));
        
        $date->setValue($dateObject);
        $dateValue = $date->getDate();
        $this->assertInstanceOf('Zend_Date', $dateValue);
        $this->assertEquals($dateString, $dateValue->get('yyyy-MM-dd'));
    }
    
    /**
     * Test is valid
     */
    public function testIsValid()
    {
        $date = new SG_Form_Element_Date('date');
        
        $dateString = '28/07/1974';
        $dateFormat = 'dd/MM/yyyy';
        $date->setDisplayFormat($dateFormat);
        $this->assertTrue($date->isValid($dateString));
        $this->assertEquals('1974-07-28', $date->getValue());
        $this->assertTrue($date->isValid('1/2/1974'));
        $this->assertEquals('1974-02-01', $date->getValue());
        
        $this->assertFalse($date->isValid('1/13/1974'));
        $this->assertEquals('1/13/1974', $date->getValue());
        
        $date = new SG_Form_Element_Date('date');
        $dateString = '07.28.1974';
        $dateFormat = 'MM.dd.yyyy';
        $date->setDisplayFormat($dateFormat);
        $this->assertTrue($date->isValid($dateString));
        $this->assertTrue($date->isValid('07/28/1974'));
        $this->assertFalse($date->isValid('28/07/1974'));
    }
    
    /**
     * Test the render function. This should render the date in the 
     * displayFormat.
     */
    public function testRenderEmptyValue()
    {
        $date = new SG_Form_Element_Date('date');
        $date->setDisplayFormat('dd.MM.yyyy');
        
        $decorators = $date->getDecorators();
        foreach($decorators AS $key => $decorator) {
            if($key === 'Zend_Form_Decorator_ViewHelper') {
                continue;
            }
        
            $date->removeDecorator($key);
        }
        
        $this->assertNotEmpty(stripos($date->render(), 'value=""'));
    }
    
    /**
     * Test the render function. This should render the date in the 
     * displayFormat.
     */
    public function testRender()
    {
        $date = new SG_Form_Element_Date('date');
        $date->setDisplayFormat('dd.MM.yyyy');
        $date->setValue('1974-07-28');
        
        $decorators = $date->getDecorators();
        foreach($decorators AS $key => $decorator) {
            if($key === 'Zend_Form_Decorator_ViewHelper') {
                continue;
            }
        
            $date->removeDecorator($key);
        }
        
        $this->assertNotEmpty(stripos($date->render(), 'value="28.07.1974"'));
    }
    
    /**
     * Helper function to reset the date_default_format value in the variable 
     * table back to the original state
     */
    protected function _revertDefaultDateFormat()
    {
        static $format;
        $vars = SG_Variables::getInstance();
        
        if(is_null($format)) {
            $format = $vars->get('date_default_format');
        }
        else {
            $vars->set('date_default_format', $format);
        }
    }
}
