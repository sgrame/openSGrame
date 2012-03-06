<?php
/**
 * @category TB
 * @package  TB_Form
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * TB_Form_Decorator_BootstrapTag
 *
 * Based on Ez_Form_Decorator_BootstrapTag
 * Wraps errors in span with class help-inline
 *
 * @category   TB
 * @package    TB_Form
 * @subpackage Decorator
 * @author     Peter Decuyper <sgrame@gmail.com>
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @link       https://github.com/sgrame/openSGrame
 */
class TB_Form_Decorator_BootstrapTag extends Zend_Form_Decorator_HtmlTag
{
    /**
     * Render content wrapped in an HTML tag
     *
     * @param  string $content
     * @return string
     */
    public function render($content)
    {
        $tag       = $this->getTag();
        $placement = $this->getPlacement();
        $noAttribs = $this->getOption('noAttribs');
        $openOnly  = $this->getOption('openOnly');
        $closeOnly = $this->getOption('closeOnly');
        $this->removeOption('noAttribs');
        $this->removeOption('openOnly');
        $this->removeOption('closeOnly');

        $attribs = null;
        if (!$noAttribs) {
            $attribs = $this->getOptions();
        }

        switch ($placement) {
            case self::APPEND:
                if ($closeOnly) {
                    return $content . $this->_getCloseTag($tag);
                }
                if ($openOnly) {
                    return $content . $this->_getOpenTag($tag, $attribs);
                }
                return $content
                     . $this->_getOpenTag($tag, $attribs)
                     . $this->_getCloseTag($tag);
            case self::PREPEND:
                if ($closeOnly) {
                    return $this->_getCloseTag($tag) . $content;
                }
                if ($openOnly) {
                    return $this->_getOpenTag($tag, $attribs) . $content;
                }
                return $this->_getOpenTag($tag, $attribs)
                     . $this->_getCloseTag($tag)
                     . $content;
            default:
                return (($openOnly || !$closeOnly) ? $this->_getOpenTag($tag, $attribs) : '')
                     . $content
                     . (($closeOnly || !$openOnly) ? $this->_getCloseTag($tag) : '');
        }
    }

}