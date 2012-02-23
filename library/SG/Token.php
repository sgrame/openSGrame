<?php
/**
 * @category SG
 * @package  Token
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_Token
 *
 * Use this to create token string.
 * See SG_Token::generate for possible formats or use one of the predefined.
 *
 * @category SG
 * @package  Token
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_Token
{
    /**
     * Method to generate a token string (unformatted)
     * 
     * The possible types are:
     * N   : generates a token of numbers
     * A   : generates a token of lower and upper alpha chars
     * L   : generates a token of lower alpha chars
     * U   : generates a token of higher alpha chars
     * AN  : generates a token of lower and upper alpha chars + numbers
     * LN : generates a token of lower alpha chars + numbers
     * UN : generates a token of upper alpha chars + numbers
     * H   : generates a token of hexadecimal chars
     * 
     * @param int
     *     length
     * @param string    
     *     type (N, A, L, U, AN, LN, UN, H)
     * 
     * @return string
     */
    public function generate($_length = 8, $_type = 'null')
    {
        // add some caching
        static $type;
        static $range;
            
        // if not cached or other type create the char range
        if(is_null($range)
            || $type !== $_type)
        {
            $type = $_type;
            $ranges = array(
                'num'     => '0123456789',
                'upper'    => 'ABCDEFGHJKLMNPQRSTUVWXYZ',
                'lower'    => 'abcdefghijkmnopqrstuvwxyz',
                'hex'    => '0123456789ABCDEF',
            );
            
            switch($type)
            {
                case 'N':
                    $range = $ranges['num'];
                    break;
                case 'H':
                    $range = $ranges['hex'];
                    break;
                    
                case 'A':
                    $range = $ranges['lower']
                        . $ranges['upper'];
                    break;
                case 'L':
                    $range = $ranges['lower'];
                    break;
                case 'U':
                    $range = $ranges['upper'];
                    break;
                    
                case 'LN':
                case 'NL':
                    $range = $ranges['num']
                        . $ranges['lower'];
                    break;
                case 'UN':
                case 'NU':
                    $range = $ranges['num']
                        . $ranges['upper'];
                    break;
                
                case 'AN':
                case 'NA':
                default:
                    $range = $ranges['num']
                        . $ranges['lower']
                        . $ranges['upper'];
                    break;
            }
        }
        
        // generate the token
        $token = '';
        $rangeLength = strlen($range) - 1;
        srand((float) microtime() * 10000000);
        for ($i = 0; $i < (int)$_length; $i++) {
            $token .= $range[rand(0, $rangeLength)];
        }
        
        return $token;
    }
    
    /**
     * Method to generate a token string (formatted)
     * 
     * It is possible to generate a token with a customized format
     * You can provide the format by:
     * 
     * any number: the number of chars
     * the format: see SG_Token::generate()
     * 
     * Example:
     * 1-8 => wil generate a <1 alphaNum>-<8 alphaNum> token
     * 1A_8ALN => wil generate a <1 alpha>-<8 alphaLowercase&numbers> token
     * 
     * @param string    
     *     the format
     * 
     * @return string
     *     the token
     */
    public function generateFormat($_format = '32H')
    {
        // add some caching
        static $parts;
        static $format;
        
        // parse the format only when not parsed before
        if(!is_array($parts) 
            || $format !== $_format
        )
        {
            $format = $_format;
            $parts = array();
            $i = 0;
            $charLast = null;
            $defArray = array(
                'type'     => '', 
                'value' => '', 
                'length'=> ''
            );
            
            $lenght = strlen($format);
            // loop through the format string and collect the parts
            for($j = 0; $j < $lenght; $j++)
            {
                // get the char
                $char = $format[$j];
                    
                // check if there is a $i++ done in the last loop
                if($i === 0 && !isset($parts[0]))
                {
                    $parts[$i] = $defArray;
                }
                
                // check if it isn't an seperator
                if(!preg_match('/[0-9ALUNH]/', $char))
                {
                    // it's a seperator
                    $i++;
                    $parts[$i] = $defArray;
                        $parts[$i]['type'] = 'sep';
                        $parts[$i]['value'] = $char;
                    $charLast = $char;
                }
                // it is an length or token string type
                else 
                {
                    if(is_numeric($char))
                    {
                        // if last char isn't num then we hav a token 
                        // without seperators
                            if(!is_numeric($charLast) 
                                && !empty($charLast))
                            {
                                $i++;
                                $parts[$i] = $defArray;
                            }
                        $parts[$i]['length'] .= $char;
                        $charLast = $char;
                    }
                    else 
                    {
                        $parts[$i]['value'] .= $char;
                        $charLast = $char;
                    }
                }
            }
        }

        // Generate the token
        $token = array();
        foreach($parts AS $part)
        {
            if($part['type'] == 'sep')
            {
                $token[] = $part['value'];
            }
            else 
            {
                $token[] = $this->generate(
                    (int)$part['length'], 
                    $part['value']
                );
            }
        }
        
        return implode('', $token);
    }
    
    /**
     * Method to generate an GUID (MS style)
     * 
     * @param void
     * 
     * @return string
     */
    public function guid()
    {
        return $this->generateFormat('8H-4H-4H-4H-12H');
    }
    
    /**
     * Method to generate an UUID
     * This uses the whole alphanumeric instead of only hexadecimal chars
     * 
     * @param
     * 
     * @return string
     */
    public function uuid()
    {
        return $this->generate(32, 'AN');
    }
    
    /**
     * Method to generate a list of unique codes
     * 
     * Possible formats:
     * int        : SG_Token::generate(int) will be used
     * any format : SG_Token::generate(format) will be used 
     *                 (see SG_Token::generate() and SG_Token::generateFormat() 
     * guid       : SG_Token::guid() will be used
     * uuid       : SG_Token::uuid() will be used
     * 
     * @param int
     *    amount of numbers to generate
     * @param mixed
     *    format or length
     * 
     * @return array
     */
    public function generateList($_amount, $_format = 'uuid')
    {
        $list = array();
        $errors = 0;
        $maxErrors = 100;
        
        switch ($_format)
        {
            case('uuid'):
                $method = 'uuid';
                break;
            case('guid'):
                $method = 'guid';
                break;
            default:
                if(is_numeric($_format))
                {
                    $method = 'generate';
                }
                else 
                {
                    $method = 'generateFormat';
                }
                break;
        }

        $i = 0;
        while($i < $_amount)
        {
            $token = $this->{$method}($_format);
            if(!in_array($token, $list))
            {
                $list[] = $token;
                $i++;
            }
            else 
            {
                $errors++;
            }
            
            if($errors > $maxErrors)
            {
                trigger_error('More then ' 
                    . $maxErrors 
                    . ' double tokens generated. '
                    . 'Check if the length and format can support ' 
                    . 'your request.',
                    E_USER_WARNING
                );
                break;
            }
            
            $i++;
        }
        
        return $list;
    }
}