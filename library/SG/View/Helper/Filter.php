<?php
/* SVN FILE $Id: Filter.php 2 2010-06-14 08:04:19Z SerialGraphics $ */
/**
 * Filter for users input
 *
 * @filesource
 * @copyright		Serial Graphics Copyright 2009
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			Sep 8, 2009
 * @version			$Revision: 2 $
 * @modifiedby		$LastChangedBy: SerialGraphics $
 * @lastmodified	$Date: 2010-06-14 10:04:19 +0200 (Mon, 14 Jun 2010) $
 */


/**
 * Helper to filter users input
 *
 * @category   SG
 * @package    SG_View
 * @subpackage Helper
 */
class SG_View_Helper_Filter extends Zend_View_Helper_Abstract
{
    /**
     * Filters and purifies user inpt
     *
     * @param	string
     * @param	array	options
     * @return 	string 	The filtered string
     */
    public function filter($_string, $_options = array())
    {
    	// filter tags
        $filter = new Zend_Filter_StripTags();
        $_string = $filter->filter($_string);
        
        // filter newlines
        $_string = str_replace(PHP_EOL, '<br />', $_string);
        
        return $_string;
    }

}
