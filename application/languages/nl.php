<?php
/**
 * Dutch language file
 *
 * @filesource
 * @copyright   Serial Graphics Copyright 2011
 * @author      Serial Graphics <info@serial-graphics.be>
 * @link      http://www.serial-graphics.be
 * @since     January 23, 2011
 * @package     SG
 * @subpackage    Lang
 */

/**
 * Lang file, used for the form validators
 */
return array(
  /* Zend_Validate errors */
  Zend_Validate_NotEmpty::IS_EMPTY  
    => 'Dit veld is verplicht',
  
  Zend_Validate_Alpha::NOT_ALPHA 
    => '\'%value%\' bevat niet alleen alfabetische karakters',
  Zend_Validate_Alpha::STRING_EMPTY 
    => '\'%value%\' moet ingevuld zijn',
    
  Zend_Validate_Alnum::NOT_ALNUM
    => '\'%value%\' bevat niet alleen alfabetische en numerieke karakters',
  
  Zend_Validate_EmailAddress::INVALID 
    => '\'%value%\' is geen geldig email adres',
  Zend_Validate_EmailAddress::INVALID_FORMAT  
    => '\'%value%\' is geen geldig email adres',
  Zend_Validate_EmailAddress::INVALID_HOSTNAME  
    => '\'%value%\' is geen geldig email adres',
  Zend_Validate_EmailAddress::INVALID_MX_RECORD 
    => '\'%value%\' is geen geldig email adres',
  Zend_Validate_EmailAddress::INVALID_SEGMENT
    => '\'%value%\' is geen geldig email adres',
  Zend_Validate_EmailAddress::DOT_ATOM 
    => '\'%value%\' is geen geldig email adres',
  Zend_Validate_EmailAddress::QUOTED_STRING 
    => '\'%value%\' is geen geldig email adres',
  Zend_Validate_EmailAddress::INVALID_LOCAL_PART 
    => '\'%value%\' is geen geldig email adres',
  Zend_Validate_EmailAddress::LENGTH_EXCEEDED 
    => '\'%value%\' is geen geldig email adres',
    
  Zend_Validate_Date::INVALID
    => '\'%value%\' is geen geldige datum',
  Zend_Validate_Date::FALSEFORMAT
    => '\'%value%\' voldoet niet aan het gevraagde formaat',
    
  Zend_Validate_StringLength::TOO_SHORT
    => 'moet minstens %min% karakters lang zijn',
  Zend_Validate_StringLength::TOO_LONG
    => 'mag maximum %max% karakters lang zijn',
    
    
  SG_Validate_Phone::INVALID_CHARS 
    => '\'%value%\' mag enkel cijfers bevatten',
  SG_Validate_Phone::INVALID_START
    => '\'%value%\' moet met een 0 beginnen',
    
  SG_Validate_Url::INVALID_URL 
    => '\'%value%\' is geen geldige URL',
    
  SG_Validate_FormIdentical::NOT_SAME_VALUE
    => 'Waarden zijn niet dezelfde',
    
  SG_Validate_CompanyNumber::INVALID
    => '\'%value%\' is geen geldige ondernemingsnummer',
  
  Zend_Validate_Identical::MISSING_TOKEN 
    => 'Gelieve op verzenden te klikken',
  Zend_Validate_Identical::NOT_SAME
    => 'Gelieve op verzenden te klikken',
    
  Zend_Validate_InArray::NOT_IN_ARRAY
    => 'Geen geldige waarde',
);
