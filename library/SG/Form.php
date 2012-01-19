<?php
/* SVN FILE $Id: Form.php 32 2010-07-25 14:32:17Z SerialGraphics $ */
/**
 * Default SG form extention
 *
 * @filesource
 * @copyright		Serial Graphics Copyright 2008
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			Jun 19, 2009
 * @package			SG
 * @subpackage		Form
 * @version			$Revision: 32 $
 * @modifiedby		$LastChangedBy: SerialGraphics $
 * @lastmodified	$Date: 2010-07-25 16:32:17 +0200 (Sun, 25 Jul 2010) $
 */

/**
 * Default SG form class
 * 
 * this adds the prefix paths for SG_Filter, SG_Validate & SG_Decorator
 *
 */
class SG_Form extends Zend_Form
{
	/**
	 * Contexts
	 * 
	 * @var		string
	 */
	const CONTEXT_SAVE 		= 'save';
	const CONTEXT_CREATE 	= 'create';
	const CONTEXT_UPDATE 	= 'update';
	const CONTEXT_DELETE 	= 'delete';
	const CONTEXT_NEXT 		= 'next';
	const CONTEXT_SEARCH    = 'search';
	const CONTEXT_LIST 		= 'search';
	const CONTEXT_FILTER 	= 'filter';
	
	/**
	 * Form context
	 * Used to determen the button label
	 * 
	 * @var 	string 
	 */
	protected $_context = self::CONTEXT_SAVE;
	
	/**
	 * Possible form contexts
	 * 
	 * @var		array
	 */
	protected $_contexts = array(
		self::CONTEXT_SAVE	=> 'core.form.button.label.save',
		self::CONTEXT_CREATE=> 'core.form.button.label.create',
		self::CONTEXT_UPDATE=> 'core.form.button.label.update',
		self::CONTEXT_DELETE=> 'core.form.button.label.delete',
		self::CONTEXT_NEXT	=> 'core.form.button.label.next',
		self::CONTEXT_SEARCH=> 'core.form.button.label.search',
		self::CONTEXT_LIST	=> 'core.form.button.label.search',
		self::CONTEXT_FILTER=> 'core.form.button.label.filter',
	);
	
	
	/**
     * Constructor
     *
     * Registers form view helper as decorator
     * 
     * @param mixed $options 
     * @return void
     */
	public function __construct($_options = null)
    {
        $this->setMethod('post')
        	 ->addPrefixPath(
        	 	'SG_Form_Element', 
        	 	'SG/Form/Element', 
        	 	'element'
        	 )
             ->addElementPrefixPath(
             	'SG_Filter', 
             	'SG/Filter/', 
             	Zend_Form_Element::FILTER
             )
             ->addElementPrefixPath(
             	'SG_Validate', 
             	'SG/Validate/', 
             	Zend_Form_Element::VALIDATE
             )
             ->addElementPrefixPath(
             	'SG_Form_Decorator',
             	'SG/Form/Decorator/',
             	Zend_Form_Element::DECORATOR
             );

    	if(is_array($_options) && isset($_options['context']))
		{
			$this->setContext($_options['context']);
		}
		     
        parent::__construct($_options);
    }
    
    /**
     * Helper method to get the label based on the context
     * 
     * @param	void
     * @return	string
     */
    protected function _getSubmitLabel()
    {
    	$label = null;
    	
    	if(isset($this->_contexts[$this->_context]))
    	{
    		$label = $this->_contexts[$this->_context];
    	}
    	
    	return $label;
    }
    
    /**
     * Set the context
     * 
     * @param	string	context
     * @return	self
     */
    public function setContext($_context, $_init = false)
    {
    	$this->_context = $_context;
    	if($_init)
    	{
    		$this->init();
    	}
    	return $this;
    }
    
    /**
     * Get the context
     * 
     * @param	void
     * @return	string
     */
    public function getContext()
    {
    	return $this-_context;
    }
}

