<?php
/* SVN FILE $Id: FormIdentical.php 2 2010-06-14 08:04:19Z SerialGraphics $ */
/**
 * Compare form values
 *
 * @filesource
 * @copyright		Serial Graphics Copyright 2008
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			Jun 19, 2009
 * @package			SG
 * @subpackage		Validate
 * @version			$Revision: 2 $
 * @modifiedby		$LastChangedBy: SerialGraphics $
 * @lastmodified	$Date: 2010-06-14 10:04:19 +0200 (Mon, 14 Jun 2010) $
 */


/**
 * Validates if a given value is identical compared to an other form value key
 *
 */
class SG_Validate_FormIdentical extends Zend_Validate_Abstract
{
	/**#@+
     * Error codes
     * @const string
     */
    const NOT_SAME_VALUE      = 'FormIdentical_notSameValues';
    /**#@-*/

    /**
     * Error messages
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_SAME_VALUE     		=> 'Values do not match',
    );
	
	/**
	 * name of the form field against who to validate
	 * 
	 * @var 	string
	 */
	protected $_contextKey;
	
	/**
	 * Constructor
	 * 
	 * @param 	string	context key against to check
	 */
	public function __construct($_contextKey = null)
	{
		$this->_contextKey = $_contextKey;
	}
	
	/**
	 * Validator
	 * 
	 * @param 	string	value to validate
	 * @param 	array	context
	 * @return 	bool	valid
	 */
	public function isValid($_value, $_context = array())
	{
		if(!is_array($_context) || !array_key_exists($this->_contextKey, $_context))
		{
			$this->_error(self::NOT_SAME_VALUE);
			return false;
		}
		
		if ($_value !== $_context[$this->_contextKey]) 
        {
            $this->_error(self::NOT_SAME_VALUE);
            return false;
        }
		
        return true;
		
	}
}