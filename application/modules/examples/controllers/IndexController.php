<?php
/**
 * @category Examples
 * @package  Controller
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 * @filesource
 */


/**
 * Examples_IndexController
 *
 * Overview of the examples
 *
 * @category Examples
 * @package  Controller
 * @author   Peter Decuyper <sgrame@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @link     https://github.com/sgrame/openSGrame
 */
class Examples_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->layout()->title = 'Examples overview';
    }

    public function indexAction()
    {
        // action body
    }


}

