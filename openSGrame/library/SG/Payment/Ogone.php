<?php
/**
 * @category SG
 * @package  Payment
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Payment_Ogone
 * 
 * Wrapper around the Ogone payment service
 *
 * @category SG
 * @package  Payment
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Payment_Ogone
{
    /**
     * Working modes
     * 
     * @var string 
     */
    const MODE_PRODUCTION = 'production';
    const MODE_TEST       = 'test';
    
    /**
     * Endpoints
     * 
     * @var string 
     */
    const ENDPOINT_PRODUCTION = 
        'https://secure.ogone.com/ncol/prod/orderstandard_utf8.asp';
    const ENDPOINT_TEST       = 
        'https://secure.ogone.com/ncol/test/orderstandard_utf8.asp';
    
    /**
     * Settings
     * 
     * @var array 
     */
    protected $_settings = array(
        'mode'        => self::MODE_PRODUCTION,
        // SHA IN protection
        'encryption'  => 'SHA-1',
        'passphrase'  => null,
        
    );
    
    /**
     * The allowed currencies 
     * 
     * Note this is public to allow easy modification, if need be. 
     * Based on @link https://github.com/marlon-be/marlon-ogone/blob/master/lib/Ogone/PaymentRequest.php
     * 
     * @var array
     */
    public $allowedCurrencies = array(
		'AED', 'ANG', 'ARS', 'AUD', 'AWG', 'BGN', 'BRL', 'BYR', 'CAD', 'CHF',
		'CNY', 'CZK', 'DKK', 'EEK', 'EGP', 'EUR', 'GBP', 'GEL', 'HKD', 'HRK',
		'HUF', 'ILS', 'ISK', 'JPY', 'KRW', 'LTL', 'LVL', 'MAD', 'MXN', 'NOK',
		'NZD', 'PLN', 'RON', 'RUB', 'SEK', 'SGD', 'SKK', 'THB', 'TRY', 'UAH',
		'USD', 'XAF', 'XOF', 'XPF', 'ZAR'
	);

	/**
     * The allowed languages 
     * 
     * Note this is public to allow easy modification, if need be. 
     * Based on @link https://github.com/marlon-be/marlon-ogone/blob/master/lib/Ogone/PaymentRequest.php
     * 
     * @var array
     */
	public $allowedLanguages = array(
		'en_US' => 'English', 'cs_CZ' => 'Czech', 'de_DE' => 'German',
		'dk_DK' => 'Danish', 'el_GR' => 'Greek', 'es_ES' => 'Spanish',
		'fr_FR' => 'French', 'it_IT' => 'Italian', 'ja_JP' => 'Japanese',
		'nl_BE' => 'Flemish', 'nl_NL' => 'Dutch', 'no_NO' => 'Norwegian',
		'pl_PL' => 'Polish', 'pt_PT' => 'Portugese', 'ru_RU' => 'Russian',
		'se_SE' => 'Swedish', 'sk_SK' => 'Slovak', 'tr_TR' => 'Turkish'
	);
    
    /**
     * Language mapping from system language to allowed Languages
     * 
     * Note this is public to allow easy modification, if need be. 
     * 
     * @var array 
     */
    public $languageMapping = array(
        'default' => 'en_US',
        'en'      => 'en_US',
        'nl'      => 'nl_BE',
        'fr'      => 'fr_FR',
        'de'      => 'de_DE',
    );
    
    /**
     * The data
     * 
     * @var array
     */
    protected $_data = array(
        'ACCEPTANCE'                       => null,
        'ACCEPTURL'                       => null,
        'ADDMATCH'                        => null,
        'ADDRMATCH'                       => null,
        'AIAGIATA'                        => null,
        'AIAIRNAME'                       => null,
        'AIAIRTAX'                        => null,
        'AIBOOKIND*XX*'                   => null,
        'AICARRIER*XX*'                   => null,
        'AICHDET'                          => null,
        'AICLASS*XX*'                      => null,
        'AICONJTI'                         => null,
        'AIDEPTCODE'                       => null,
        'AIDESTCITY*XX*'                   => null,
        'AIDESTCITYL*XX*'                  => null,
        'AIEXTRAPASNAME*XX*'               => null,
        'AIEYCD'                           => null,
        'AIFLDATE*XX*'                     => null,
        'AIFLNUM*XX*'                      => null,
        'AIGLNUM'                          => null,
        'AIINVOICE'                        => null,
        'AIIRST'                           => null,
        'AIORCITY*XX*'                     => null,
        'AIORCITYL*XX*'                    => null,
        'AIPASNAME'                        => null,
        'AIPROJNUM'                        => null,
        'AISTOPOV*XX*'                     => null,
        'AITIDATE'                         => null,
        'AITINUM'                          => null,
        'AITINUML*XX*'                     => null,
        'AITYPCH'                          => null,
        'AIVATAMNT'                        => null,
        'AIVATAPPL'                        => null,
        'ALIAS'                            => null,
        'ALIASOPERATION'                   => null,
        'ALIASUSAGE'                       => null,
        'ALLOWCORRECTION'                  => null,
        'AMOUNT'                           => null,
        'AMOUNT*XX*'                       => null,
        'AMOUNTHTVA'                       => null,
        'AMOUNTTVA'                        => null,
        'BACKURL'                          => null,
        'BATCHID'                          => null,
        'BGCOLOR'                          => null,
        'BLVERNUM'                         => null,
        'BRAND'                            => null,
        'BRANDVISUAL'                      => null,
        'BUTTONBGCOLOR'                    => null,
        'BUTTONTXTCOLOR'                   => null,
        'CANCELURL'                        => null,
        'CARDNO'                           => null,
        'CATALOGURL'                       => null,
        'CAVV_3D'                          => null,
        'CAVVALGORITHM_3D'                 => null,
        'CERTID'                           => null,
        'CHECK_AAV'                        => null,
        'CIVILITY'                         => null,
        'CN'                               => null,
        'COM'                              => null,
        'COMPLUS'                          => null,
        'COSTCENTER'                       => null,
        'COSTCODE'                         => null,
        'CREDITCODE'                       => null,
        'CUID'                             => null,
        'CURRENCY'                         => null,
        'CVC'                              => null,
        'CVCFLAG'                          => null,
        'DATA'                             => null,
        'DATATYPE'                         => null,
        'DATEIN'                           => null,
        'DATEOUT'                          => null,
        'DECLINEURL'                       => null,
        'DEVICE'                           => null,
        'DISCOUNTRATE'                     => null,
        'DISPLAYMODE'                      => null,
        'ECI'                              => null,
        'ECI_3D'                           => null,
        'ECOM_BILLTO_POSTAL_CITY'          => null,
        'ECOM_BILLTO_POSTAL_COUNTRYCODE'   => null,
        'ECOM_BILLTO_POSTAL_NAME_FIRST'    => null,
        'ECOM_BILLTO_POSTAL_NAME_LAST'     => null,
        'ECOM_BILLTO_POSTAL_POSTALCODE'    => null,
        'ECOM_BILLTO_POSTAL_STREET_LINE1'  => null,
        'ECOM_BILLTO_POSTAL_STREET_LINE2'  => null,
        'ECOM_BILLTO_POSTAL_STREET_NUMBER' => null,
        'ECOM_CONSUMERID'                  => null,
        'ECOM_CONSUMER_GENDER'             => null,
        'ECOM_CONSUMEROGID'                => null,
        'ECOM_CONSUMERORDERID'             => null,
        'ECOM_CONSUMERUSERALIAS'           => null,
        'ECOM_CONSUMERUSERPWD'             => null,
        'ECOM_CONSUMERUSERID'              => null,
        'ECOM_PAYMENT_CARD_EXPDATE_MONTH'  => null,
        'ECOM_PAYMENT_CARD_EXPDATE_YEAR'   => null,
        'ECOM_PAYMENT_CARD_NAME'           => null,
        'ECOM_PAYMENT_CARD_VERIFICATION'   => null,
        'ECOM_SHIPTO_C OMPANY'             => null,
        'ECOM_SHIPTO_DOB'                  => null,
        'ECOM_SHIPTO_ONLINE_EMAIL'         => null,
        'ECOM_SHIPTO_POSTAL_CITY'          => null,
        'ECOM_SHIPTO_POSTAL_COUNTRYCODE'   => null,
        'ECOM_SHIPTO_POSTAL_NAME_FIRST'    => null,
        'ECOM_SHIPTO_POSTAL_NAME_LAST'     => null,
        'ECOM_SHIPTO_POSTAL_NAME_PREFIX'   => null,
        'ECOM_SHIPTO_POSTAL_POSTALC ODE'   => null,
        'ECOM_SHIPTO_POSTAL_STREET_LINE1'  => null,
        'ECOM_SHIPTO_POSTAL_STREET_LINE2'  => null,
        'ECOM_SHIPTO_POSTAL_STREET_NUMBER' => null,
        'ECOM_SHIPTO_TELECOM_FAX_NUMBER'   => null,
        'ECOM_SHIPTO_TELECOM_PHONE_NUMBER' => null,
        'ECOM_SHIPTO_TVA'                  => null,
        'ED'                               => null,
        'EMAIL'                            => null,
        'EXCEPTIONURL'                     => null,
        'EXCLPMLIST'                       => null,
        'EXECUTIONDATE*XX*'                => null,
        'FACEXC L*XX*'                     => null,
        'FACTOTAL*XX*'                     => null,
        'FIRSTCALL'                        => null,
        'FLAG3D'                           => null,
        'FONTTYPE'                         => null,
        'FORCECODE1'                       => null,
        'FORCECODE2'                       => null,
        'FORCECODEHASH'                    => null,
        'FORCEPROCESS'                     => null,
        'FORCETP'                          => null,
        'GENERIC_BL'                       => null,
        'GIROPAY_ACCOUNT_NUMBER'           => null,
        'GIROPAY_BLZ'                      => null,
        'GIROPAY_OWNER_NAME'               => null,
        'GLOBORDERID'                      => null,
        'GUID'                             => null,
        'HDFONTTYPE'                       => null,
        'HDTBLBGCOLOR'                     => null,
        'HDTBLTXTCOLOR'                    => null,
        'HEIGHTFRAME'                      => null,
        'HOMEURL'                          => null,
        'HTTP_ACCEPT'                      => null,
        'HTTP_USER_AGENT'                  => null,
        'INCLUDE_BIN'                      => null,
        'INCLUDE_COUNTRIES'                => null,
        'INVDATE'                          => null,
        'INVDISCOUNT'                      => null,
        'INVLEVEL'                         => null,
        'INVORDERID'                       => null,
        'ISSUERID'                         => null,
        'IST_MOBILE'                       => null,
        'ITEM_COUNT'                       => null,
        'ITEMATTRIBUTES*XX*'               => null,
        'ITEMCATEGORY*XX*'                 => null,
        'ITEMCOMMENTS*XX*'                 => null,
        'ITEMDESC*XX*'                     => null,
        'ITEMDISCOUNT*XX*'                 => null,
        'ITEMID*XX*'                       => null,
        'ITEMNAME*XX*'                     => null,
        'ITEMPRICE*XX*'                    => null,
        'ITEMQUANT*XX*'                    => null,
        'ITEMQUANTORIG*XX*'                => null,
        'ITEMUNITOFMEASURE*XX*'            => null,
        'ITEMVAT*XX*'                      => null,
        'ITEMVATCODE*XX*'                  => null,
        'ITEMWEIGHT*XX*'                   => null,
        'LANGUAGE'                         => null,
        'LEVEL1AUTHCPC'                    => null,
        'LIDEXCL*XX*'                      => null,
        'LIMITCLIENTSCRIPTUSAGE'           => null,
        'LINE_REF'                         => null,
        'LINE_REF1'                        => null,
        'LINE_REF2'                        => null,
        'LINE_REF3'                        => null,
        'LINE_REF4'                        => null,
        'LINE_REF5'                        => null,
        'LINE_REF6'                        => null,
        'LIST_BIN'                         => null,
        'LIST_COUNTRIES'                   => null,
        'LOGO'                             => null,
        'MAXITEMQUANT*XX*'                 => null,
        'MERCHANTID'                       => null,
        'MODE'                             => null,
        'MTIME'                            => null,
        'MVER'                             => null,
        'NETAMOUNT'                        => null,
        'OPERATION'                        => null,
        'ORDERID'                          => null,
        'ORDERSHIPCOST'                    => null,
        'ORDERSHIPMETH'                    => null,
        'ORDERSHIPTAX'                     => null,
        'ORDERSHIPTAXCODE'                 => null,
        'ORIG'                             => null,
        'OR_INVORDERID'                    => null,
        'OR_ORDERID'                       => null,
        'OWNERADDRESS'                     => null,
        'OWNERADDRESS2'                    => null,
        'OWNERCTY'                         => null,
        'OWNERTELNO'                       => null,
        'OWNERTELNO2'                      => null,
        'OWNERTOWN'                        => null,
        'OWNERZIP'                         => null,
        'PAIDAMOUNT'                       => null,
        'PARAMPLUS'                        => null,
        'PARAMVAR'                         => null,
        'PAYID'                            => null,
        'PAYMETHOD'                        => null,
        'PM'                               => null,
        'PMLIST'                           => null,
        'PMLISTPMLISTTYPE'                 => null,
        'PMLISTTYPE'                       => null,
        'PMLISTTYPEPMLIST'                 => null,
        'PMTYPE'                           => null,
        'POPUP'                            => null,
        'POST'                             => null,
        'PSPID'                            => null,
        'PSWD'                             => null,
        'REF'                              => null,
        'REFER'                            => null,
        'REFID'                            => null,
        'REFKIND'                          => null,
        'REF_CUSTOMERID'                   => null,
        'REF_CUSTOMERREF'                  => null,
        'REGISTRED'                        => null,
        'REMOTE_ADDR'                      => null,
        'REQGENFIELDS'                     => null,
        'RTIMEOUT'                         => null,
        'RTIMEOUTREQUESTEDTIMEOUT'         => null,
        'SCORINGCLIENT'                    => null,
        'SETT_BATCH'                       => null,
        'SID'                              => null,
        'STATUS_3D'                        => null,
        'SUBSCRIPTION_ID'                  => null,
        'SUB_AM'                           => null,
        'SUB_AMOUNT'                       => null,
        'SUB_COM'                          => null,
        'SUB_C OMMENT'                     => null,
        'SUB_CUR'                          => null,
        'SUB_ENDDATE'                      => null,
        'SUB_ORDERID'                      => null,
        'SUB_PERIOD_MOMENT'                => null,
        'SUB_PERIOD_MOMENT_M'              => null,
        'SUB_PERIOD_MOMENT_WW'             => null,
        'SUB_PERIOD_NUMBER'                => null,
        'SUB_PERIOD_NUMBER_D'              => null,
        'SUB_PERIOD_NUMBER_M'              => null,
        'SUB_PERIOD_NUMBER_WW'             => null,
        'SUB_PERIOD_UNIT'                  => null,
        'SUB_STARTDATE'                    => null,
        'SUB_STATUS'                       => null,
        'TAAL'                             => null,
        'TAXINCLUDED*XX*'                  => null,
        'TBLBGCOLOR'                       => null,
        'TBLTXTCOLOR'                      => null,
        'TID'                              => null,
        'TITLE'                            => null,
        'TOTALAMOUNT'                      => null,
        'TP'                               => null,
        'TRACK2'                           => null,
        'TXTBADDR2'                        => null,
        'TXTCOLOR'                         => null,
        'TXTOKEN'                          => null,
        'TXTOKENTXTOKENPAYPAL'             => null,
        'TYPE_COUNTRY'                     => null,
        'UCAF_AUTHENTICATION_DATA'         => null,
        'UCAF_PAYMENT_CARD_CVC2'           => null,
        'UCAF_PAYMENT_CARD_EXPDATE_MONTH'  => null,
        'UCAF_PAYMENT_CARD_EXPDATE_YEAR'   => null,
        'UCAF_PAYMENT_CARD_NUMBER'         => null,
        'USERID'                           => null,
        'USERTYPE'                         => null,
        'VERSION'                          => null,
        'WBTU_MSISDN'                      => null,
        'WBTU_ORDERID'                     => null,
        'WEIGHTUNIT'                       => null,
        'WIN3DS'                           => null,
        'WITHROOT'                         => null,
    );
    
    /**
     * Tracks columns where data has been updated. 
     * 
     * Allows lighter data retrieval
     *
     * @var array
     */
    protected $_modifiedFields = array();
    
    
    /**
     * Class constructor
     * 
     * @param array $settings 
     */
    public function __construct($settings = array())
    {
        if (!empty($settings) && is_array($settings)) {
            foreach ($settings AS $key => $setting) {
                if (!array_key_exists($key, $this->_settings)) {
                    continue;
                }
                
                $this->_settings[$key] = $setting;
            }
        }
    }
    
    
    /**
     * Set the PSPID (the merchant unique identifier)
     * 
     * @param string
     * 
     * @return SG_Payment_Ogone
     */
    public function setPSPID($PSPID)
    {
        $this->PSPID = $PSPID;
        return $this;
    }
    
    /**
     * Set the order id
     * 
     * The order ID may not be longer then 30 characters.
     * 
     * @param string $orderID
     * 
     * @return SG_Payment_Ogone
     * 
     * @throws SG_Payment_Exception 
     */
    public function setOrderID($orderID)
    {
        if (strlen($orderID) > 30) {
            throw new SG_Payment_Exception(
                'Order ID may not be longer then 30 characters'
            );
        }
        
        $this->orderID = $orderID;
        return $this;
    }
    
    /**
     * Set the amount
     * 
     * This will automatically transform the amount into the Ogone excepted 
     * value (= amount * 100)
     * 
     * @param float $amount
     * 
     * @return SG_Payment_Ogone 
     */
    public function setAmount($amount)
    {
        $this->amount = (int)($amount * 100);
        return $this;
    }
    
    /**
     * Set the currency
     * 
     * @param string $currency
     * 
     * @return SG_Payment_Ogone
     * 
     * @throws SG_Payment_Exception 
     *     If the given currency is not supported
     */
    public function setCurrency($currency)
    {
        if (!in_array($currency, $this->allowedCurrencies)) {
            throw new SG_Payment_Exception(
                'Currency "' . $currency . '" not supported.'
            );
        }
        
        $this->currency = $currency;
        
        return $this;
    }
    
    /**
     * Set the language
     * 
     * Short version (eg. en) and long version (eg. en_US) are both supported.
     * 
     * @param string $language
     * 
     * @return SG_Payment_Ogone
     */
    public function setLanguage($language)
    {
        if (isset($this->allowedLanguages[$language])) {
            $this->language = $language;
        }
        elseif (isset($this->languageMapping[$language])) {
            $this->language = $this->languageMapping[$language];
        }
        else {
            $this->language = $this->languageMapping['default'];
        }
        
        return $this;
    }
    
    
    /**
     * Retrieve row field value
     *
     * @param  string $columnName 
     *     The user-specified column name.
     * @return string             
     *     The corresponding column value.
     * 
     * @throws SG_Payment_Exception 
     *     If the $columnName is not a column in the data array.
     */
    public function __get($columnName)
    {
        $columnName = $this->_transformColumn($columnName);
        $this->_checkColumnName($columnName);
        return $this->_data[$columnName];
    }

    /**
     * Set row field value
     *
     * @param  string $columnName 
     *     The column key.
     * @param  mixed  $value
     *     The value for the property.
     * 
     * @return void
     * 
     * @throws SG_Payment_Exception 
     *     If the $columnName is not a column in the data array.
     */
    public function __set($columnName, $value)
    {
        $columnName = $this->_transformColumn($columnName);
        $this->_checkColumnName($columnName);
        
        $this->_data[$columnName]           = $value;
        $this->_modifiedFields[$columnName] = true;
    }
    
    /**
     * Unset row field value
     *
     * @param string $columnName 
     *     The column key.
     * 
     * @return SG_Payment_Ogone
     * 
     * @throws SG_Payment_Exception
     */
    public function __unset($columnName)
    {
        $columnName = $this->_transformColumn($columnName);
        $this->_checkColumnName($columnName);
        $this->_data[$columnName] = null;
        if (isset($this->_modifiedFields[$columnName])) {
            unset($this->_modifiedFields[$columnName]);
        }
        return $this;
    }

    /**
     * Test existence of row field
     *
     * @param string $columnName   
     *     The column key.
     * 
     * @return boolean
     */
    public function __isset($columnName)
    {
        $columnName = $this->_transformColumn($columnName);
        return array_key_exists($columnName, $this->_modifiedFields);
    }
    
    /**
     * Transform a column name from the user-specified form
     * to the physical form used in the data array.
     *
     * @param string $columnName 
     *     Column name given.
     * 
     * @return string 
     *     The column name after transformation applied (none by default).
     * 
     * @throws SG_Payment_Exception 
     *     If the $columnName is not a string.
     */
    protected function _transformColumn($columnName)
    {
        if (!is_string($columnName)) {
            throw new Zend_Db_Table_Row_Exception(
                'Specified column is not a string'
            );
        }
        // Column names are in uppercase
        return strtoupper($columnName);
    }
    
    /**
     * Get the payment service endpoint
     * 
     * @param void
     * 
     * @return string 
     */
    public function getEndpoint()
    {
        switch ($this->_settings['mode']) {
            case self::MODE_PRODUCTION:
                return self::ENDPOINT_PRODUCTION;
                break;
            
            case self::MODE_TEST:
                return self::ENDPOINT_TEST;
                break;
        }
    }
    
    /**
     * Create/Get the SHA hash
     * 
     * @param void
     * 
     * @return string 
     */
    public function getHash()
    {
        $data = $this->getData();
        ksort($data);
        
        $string = array();
        
        foreach ($data AS $fieldname => $value) {
            $string[] = $fieldname 
                . '=' 
                . $value 
                . $this->_settings['passphrase'];
        }
        $string = implode(null, $string);
        
        $hash = null;
        switch (strtoupper($this->_settings['encryption'])) {
            case 'SHA-1':
                $hash = sha1($string);
                break;
            case 'SHA-256':
                $hash = sha256($string);
                break;
            
            default:
                throw new SG_Payment_Exception(
                    'Encryption type "' . $this->_settings['encryption'] . '" not supported' 
                );
        }
        $hash = strtoupper($hash);
        
        return $hash;
    }
    
    /**
     * Get the data array.
     * 
     * @param bool $all
     *     Get all data, by default only modified data
     * 
     * @return array 
     */
    public function getData($all = false)
    {
        if ($all) {
            return $this->_data;
        }
        
        $data = array();
        foreach ($this->_modifiedFields AS $columnName => $changed) {
            if (!$changed) {
                continue;
            }
            $data[$columnName] = $this->_data[$columnName];
        }
        
        return $data;
    }
    
    
    /**
     * Check if the columnname exists within the data array
     * 
     * @param string $fieldname
     * 
     * @return bool
     * 
     * @throws SG_Payment_Exception 
     *     If the $columnName is not a column in the data array.
     */
    protected function _checkColumnName($columnName)
    {
        if (!array_key_exists($columnName, $this->_data)) {
            throw new SG_Payment_Exception(
                "Specified column \"$columnName\" is not in the row"
            );
        }
    }
}

