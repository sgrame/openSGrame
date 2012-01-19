<?php
/* SVN FILE $Id: Phone.php 2 2010-06-14 08:04:19Z SerialGraphics $ */
/**
 * Phone numbre input filter
 *
 * @filesource
 * @copyright		Serial Graphics Copyright 2008
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			Jun 19, 2009
 * @package			SG
 * @subpackage		Filter
 * @version			$Revision: 2 $
 * @modifiedby		$LastChangedBy: SerialGraphics $
 * @lastmodified	$Date: 2010-06-14 10:04:19 +0200 (Mon, 14 Jun 2010) $
 */

/**
 * Removes all non numerique values from a phone string
 * 
 * Options:
 *   - allowWhiteSpace: allow whitespaces in the number
 * 
 * @category   Zend
 * @package    Zend_Filter
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class SG_Filter_Phone extends Zend_Filter_Alnum
{
    /**
     * Defined by Zend_Filter_Interface
     *
     * Returns the string $value, removing all but alphabetic and digit characters
     *
     * @param  string $value
     * @return string
     */
    public function filter($value)
    {
        $whiteSpace = $this->allowWhiteSpace ? '\s' : '';
        
        $pattern = '/[^\+0-9' . $whiteSpace . ']/';
        return preg_replace($pattern, '', (string) $value);
    }
}
