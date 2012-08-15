<?php
/**
 * Sortable form element
 * 
 * @group SG
 * @group SG_Form
 * @group SG_Form_Element
 */
class SG_Form_Element_SortableTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test the function to get all sortables at once.
     */
    public function testGetLists()
    {
        $sortable = new SG_Form_Element_Sortable('sortable');
        
        $string   = "section[]=31&section[]=5&section[]=3";
        $expected = array('section' => array(31, 5, 3));
        
        $sortable->setValue($string);
        $this->assertEquals($expected, $sortable->getLists());
    }
    
    /**
     * Test the function with an empty value 
     */
    public function testGetListsEmpty()
    {
        $sortable = new SG_Form_Element_Sortable('sortable');
        
        $this->assertEquals(array(), $sortable->getLists());
    }
    
    /**
     * Test the function to get a specific sortable out of the array.
     */
    public function testGetList()
    {
        $sortable = new SG_Form_Element_Sortable('sortable');
        
        $string   = "section[]=31&section[]=5&section[]=3&test[]=12&test[]=21";
        $expected = array(31, 5, 3);
        
        $sortable->setValue($string);
        $this->assertEquals($expected, $sortable->getList('section'));
    }
    
    /**
     * Test the function with an empty value 
     */
    public function testGetListEmpty()
    {
        $sortable = new SG_Form_Element_Sortable('sortable');
        
        $this->assertEquals(array(), $sortable->getList('foo'));
    }
}
