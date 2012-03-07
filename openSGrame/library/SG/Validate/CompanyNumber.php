<?php
/**
 * SG Validator for Company Numbers
 *
 * @filesource
 * @copyright		Serial Graphics Copyright 2009
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			Oct 29, 2009
 * @version			$Revision: 2 $
 * @modifiedby		$LastChangedBy: SerialGraphics $
 * @lastmodified	$Date: 2012-03-06 23:31:33 +0100 (Tue, 06 Mar 2012) $
 */
 

/**
 * Checks if a company number has the right syntax and checksum
 * 
 */
class SG_Validate_CompanyNumber extends Zend_Validate_Abstract 
{
	/**
	 * Error codes
	 * 
	 * @var string
	 */
    const INVALID = 'companyNumberInvalid';
    
    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID => "'%value%' is not a valid company number",
    );

    /**
     * The validation pattern 
     */
    const PATTERN = '/^[01][0-9]{3}[\.][0-9]{3}[\.][0-9]{3}$/';
    
    
    /**
     *
     * @param  string $value
     *
     * @return boolean  
     */
    public function isValid ($_value)
    {   
    	$this->_setValue($_value);
    	 
        //check if given number matches the pattern for a companyNumber
        if (!preg_match(self::PATTERN, $_value))
        {
            $this->_error(self::INVALID);
            return false;
        }
        
        //check if the number matches to mod97 check
        $number = preg_replace("/[^([:digit:])]/", '', $_value);
        $checknr = substr($number,0,8);
		$controle = substr($number,8,2);
		$mod97 = 97-fmod($checknr,97);
		if ($mod97 == 0) $mod97 = 97;
		
		if ($controle != $mod97)
		{
		    $this->_error(self::INVALID);
            return false;
		}
        
        return true;
    }
    
}