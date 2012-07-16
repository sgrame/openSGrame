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
 * SG_Payment_Ogone_Response
 * 
 * Wrapper around the Ogone payment service response
 *
 * @category SG
 * @package  Payment
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Payment_Ogone_Response extends SG_Payment_Ogone_Abstract
{
    /**
     * The data
     * 
     * Add all the possible columnNames here as array keys in UPPERCASE.
     * This will be the only columnNames allowed to add data to the object.
     * 
     * @var array
     */
    protected $_data = array(
        'ORDERID'    => null,
        'CURRENCY'   => null,
        'AMOUNT'     => null,
        'PM'         => null,
        'ACCEPTANCE' => null,
        'STATUS'     => null,
        'CARDNO'     => null,
        'ED'         => null,
        'CN'         => null,
        'TRXDATE'    => null,
        'PAYID'      => null,
        'NCERROR'    => null,
        'BRAND'      => null,
        'IP'         => null,
    );
    
    /**
     * The SHA signature from the response
     * 
     * @var string 
     */
    protected $_shaSignature = null;
    
    /**
     * Payment status codes
     * 
     * @var array 
     */
    protected $_statusses = array(
        0  => 'Incomplete or invalid',
        1  => 'Cancelled by client',
        2  => 'Authorization refused',
        4  => 'Order stored',
        40 => 'Stored waiting external result',
        41 => 'Waiting client payment',
        5  => 'Authorized',
        50 => 'Authorized waiting external result',
        51 => 'Authorization waiting',
        52 => 'Authorization not known',
        55 => 'Stand-by',
        56 => 'OK with scheduled payments',
        57 => 'Error in scheduled payments',
        59 => 'Authoriz. to get manually',
        6  => 'Authorized and cancelled',
        61 => 'Author. deletion waiting',
        62 => 'Author. deletion uncertain',
        63 => 'Author. deletion refused',
        64 => 'Authorized and cancelled',
        7  => 'Payment deleted',
        71 => 'Payment deletion pending',
        72 => 'Payment deletion uncertain',
        73 => 'Payment deletion refused',
        74 => 'Payment deleted',
        75 => 'Deletion processed by merchant',
        8  => 'Refund',
        81 => 'Refund pending',
        82 => 'Refund uncertain',
        83 => 'Refund refused',
        84 => 'Payment declined by the acquirer',
        85 => 'Refund processed by merchant',
        9  => 'Payment requested',
        91 => 'Payment processing',
        92 => 'Payment uncertain',
        93 => 'Payment refused',
        94 => 'Refund declined by the acquirer',
        95 => 'Payment processed by merchant',
        99 => 'Being processed',
    );
    
    
    /**
     * Set multiple data lines at once from key => value array
     * 
     * @param array $data
     * 
     * @return SG_Payment_Ogone_Response
     */
    public function addData($data)
    {
        foreach ($data AS $key => $value) {
            if (strtoupper($key) === 'SHASIGN') {
                $this->setShaSignature($value);
                continue;
            }
            
            $this->{$key} = $value;
        }
        
        return $this;
    }
    
    /**
     * Set the SHA Signature
     * 
     * @param string $shaSignature
     * 
     * @return SG_Payment_Ogone_Response
     */
    public function setShaSignature($shaSignature)
    {
        $this->_shaSignature = $shaSignature;
        return $this;
    }
    
    /**
     * Get the SHA Signature
     * 
     * @param void
     * 
     * @return string 
     */
    public function getShaSignature()
    {
        return $this->_shaSignature;
    }
    
    /**
     * Validate the SHA sign 
     * 
     * @param void
     * 
     * @return bool
     */
    public function isShaSignatureValid()
    {
        return ($this->getHash() === $this->getShaSignature());
    }
    
    /**
     * Get the response description
     * 
     * @param void
     * 
     * @return string 
     */
    public function getResponseCodeDescription()
    {
        $code = (int)$this->status;
        
        if (isset($this->_statusses[$code])) {
            return $this->_statusses[$code];
        }
        
        return 'Unknown';
    }
}

