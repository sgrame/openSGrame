<?php
/* SVN FILE $Id: Name.php 2 2010-06-14 08:04:19Z SerialGraphics $ */
/**
 * Name filter
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
 * Filters a string with additional support for . - and &
 */
class SG_Filter_Name extends Zend_Filter_Alpha 
{
	/**
	 * Constructor
	 * 
	 * @param 	bool	allow white space
	 */
	public function __construct($allowWhiteSpace = true)
	{
		parent::__construct($allowWhiteSpace);
	}
	
    /**
     * Defined by Zend_Filter_Interface
     *
     * Returns the string $value, removing all but alphabetic, . and - characters
     *
     * @param  string $value
     * @return string
     */
	public function filter($value)
    {
        $whiteSpace = $this->allowWhiteSpace ? '\s' : '';
        if (!self::$_unicodeEnabled) {
            // POSIX named classes are not supported, use alternative a-zA-Z match
            $pattern = '/[^a-zA-Z\.\-' . $whiteSpace . ']/';
        } else if (self::$_meansEnglishAlphabet) {
            //The Alphabet means english alphabet.
            $pattern = '/[^a-zA-Z\.\-'  . $whiteSpace . ']/u';
        } else {
            //The Alphabet means each language's alphabet.
            $pattern = '/[^\p{L}\.\-' . $whiteSpace . ']/u';
        }

        return preg_replace($pattern, '', (string) $value);
    }
}
