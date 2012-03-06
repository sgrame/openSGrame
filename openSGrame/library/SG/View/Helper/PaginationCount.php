<?php
/**
 * @category SG
 * @package  View
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * SG_View_Helper_Count
 *
 * Helper to extract the items count from a pagination
 *
 * @category SG
 * @package  View
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class SG_View_Helper_PaginationCount
{
    /**
     * Possible styles
     * 
     * @var string
     */
    const STYLE_LITE    = 'lite';
    const STYLE_DEFAULT = 'default';
    const STYLE_FULL    = 'full';
  
    /**
     * View instance
     *
     * @var Zend_View_Instance
     */
    public $view = null;

    /**
     * Default view partial
     *
     * @var string|array
     */
    protected static $_defaultViewPartial = null;

    /**
     * Sets the view instance.
     *
     * @param  Zend_View_Interface $view View instance
     * @return Zend_View_Helper_PaginationControl
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * Renders a string describing the number of items found 
     * and the current position of the pagination
     *
     * @param  Zend_Paginator $paginator
     * @return string
     */
    public function paginationCount(Zend_Paginator $paginator)
    {
        $pages = $paginator->getPages();
        
        //var_dump($pages); die;
      
        if(!$pages->pageCount) {
            return $this->view->t('No results');
        }
        
        if($pages->pageCount < 2) {
            return $this->view->t('%d in total', $pages->totalItemCount);
        }
        
        return $this->view->t(
            '%1$d in total, %2$d-%3$d shown',
            $pages->totalItemCount,
            $pages->firstItemNumber,
            $pages->lastItemNumber
        );
      
        
    }
}
