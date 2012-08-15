<?php
/**
 * Sortable base form
 * 
 * @group SG
 * @group SG_Form
 */
class SG_Form_SortableTest extends SG_Test_PHPUnit_ControllerTestCase
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
     * Test the init function with no specific options.
     */
    public function testInit()
    {
        $form = new SG_Form_Sortable();
        
        $this->assertInstanceOf(
            'SG_Form_Element_Sortable', 
            $form->getElement('sortable_data')
        );
        $this->assertInstanceOf(
            'Zend_Form_Element_Submit', 
            $form->getElement('cancel')
        );
    }
    
    /**
     * Test the init function with specific options
     */
    public function testInitWithOptions()
    {
        $options = array(
            // change the sortable element name
            'sortableId' => 'testdata',
            // no cancel button
            'hasCancelButton' => false,
        );
        
        $form = new SG_Form_Sortable($options);
        
        $this->assertNull(
            $form->getElement('sortable_data')
        );
        $this->assertInstanceOf(
            'SG_Form_Element_Sortable', 
            $form->getElement('testdata')
        );
        $this->assertNull(
            $form->getElement('cancel')
        );
    }
    
    /**
     * Test the get sortbale element 
     */
    public function testGetElementSortable()
    {
        $form = new SG_Form_Sortable();
        $element = $form->getElementSortable();
        
        $this->assertInstanceOf(
            'SG_Form_Element_Sortable', 
            $element
        );
        $this->assertEquals('sortable_data', $element->getId());
        
        
        $form = new SG_Form_Sortable(array('sortableId' => 'foo_id_test'));
        $element = $form->getElementSortable();
        
        $this->assertInstanceOf(
            'SG_Form_Element_Sortable', 
            $element
        );
        $this->assertEquals('foo_id_test', $element->getId());
        
    }
}
