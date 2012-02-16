<?php
/* SVN FILE $Id: T.php 2 2010-06-14 08:04:19Z SerialGraphics $ */
/**
 * Shorter translate function
 *
 * @filesource
 * @copyright        Serial Graphics Copyright 2009
 * @author            Serial Graphics <info@serial-graphics.be>
 * @link            http://www.serial-graphics.be
 * @since            Dec 15, 2009
 * @version            $Revision: 2 $
 * @modifiedby        $LastChangedBy: SerialGraphics $
 * @lastmodified    $Date: 2010-06-14 10:04:19 +0200 (Mon, 14 Jun 2010) $
 */

/**
 * Translate view helper
 *
 */
class SG_View_Helper_T extends Zend_View_Helper_Translate 
{
    /**
     * Translate a message
     * You can give multiple params or an array of params.
     * If you want to output another locale just set it as last single parameter
     * Example 1: translate('%1\$s + %2\$s', $value1, $value2, $locale);
     * Example 2: translate('%1\$s + %2\$s', array($value1, $value2), $locale);
     *
     * @param  string $messageid Id of the message to be translated
     * @return string Translated message
     */
    public function t($messageid = null)
    {
        $args = func_get_args();
        
        //remove first argument ($messageId)
        array_shift($args);

        return $this->translate($messageid, $args);
    }
}