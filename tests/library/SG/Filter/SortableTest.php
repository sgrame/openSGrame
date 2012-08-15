<?php
/**
 * @group SG
 * @group SG_Filter
 */
class SG_Filter_SortableTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test the filter with empty value
     */
    public function testFilterEmptyValue()
    {
        $filter = new SG_Filter_Sortable();
        
        $this->assertEquals(array(), $filter->filter(null));
    }
    
    /**
     * Test the filter with a valid string 
     */
    public function testFilterValidString()
    {
        $string   = "section[]=31&section[]=5&section[]=3";
        $expected = array('section' => array(31, 5, 3));
        
        $filter = new SG_Filter_Sortable();
        $this->assertEquals($expected, $filter->filter($string));
    }
    
    /**
     * Test with bad string 
     */
    public function testBadString()
    {
        $string   = "section-31";
        $expected = array('section-31' => '');
        
        $filter = new SG_Filter_Sortable();
        $this->assertEquals($expected, $filter->filter($string));
    }
}