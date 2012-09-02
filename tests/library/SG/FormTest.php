<?php
/**
 * @group SG
 * @group SG_FormTest
 */
class SG_FormTest extends SG_Test_PHPUnit_ControllerTestCase
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
     * Test the addCsrf method 
     * 
     * This will test the method to automatically add a crsf element to a form.
     */
    public function testAddHash()
    {
        // default element name
        $form = new SG_Form();
        $this->assertInstanceOf('SG_Form', $form->addHash());
        
        $element = $form->getElement('hash_sg_form');
        $this->assertInstanceOf('Zend_Form_Element_Hash', $element);
        $this->assertEquals('hash_sg_form', $element->getName());
        
        // custom name
        $fooName = 'foo_hashName';
        $form = new SG_Form();
        $form->addHash($fooName);
        
        $element = $form->getElement($fooName);
        $this->assertInstanceOf('Zend_Form_Element_Hash', $element);
        $this->assertEquals($fooName, $element->getName());
        
    }
    
    
}