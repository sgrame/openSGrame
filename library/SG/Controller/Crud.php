<?php
/* SVN FILE $Id: Crud.php 2 2010-06-14 08:04:19Z SerialGraphics $ */
/**
 * SG CRUD controller
 *
 * @filesource
 * @copyright		Serial Graphics Copyright 2009
 * @author			Serial Graphics <info@serial-graphics.be>
 * @link			http://www.serial-graphics.be
 * @since			Dec 31, 2009
 * @version			$Revision: 2 $
 * @modifiedby		$LastChangedBy: SerialGraphics $
 * @lastmodified	$Date: 2010-06-14 10:04:19 +0200 (Mon, 14 Jun 2010) $
 */
 
/**
 * Crud controller is used to have default CRUD functionality.
 * 
 * It supports by default 4 actions:
 * list		: List all existing records (this is by default the indexAction)
 * detail	: Detail for one record
 * create	: Create a new record
 * update	: Update an existing record
 * delete	: Delete an existing record
 * 
 */
class SG_Controller_Crud extends Zend_Controller_Action 
{
	/**
	 * Default actions settings
	 * 
	 * Following actions are supported by this controller
	 * list   : list of all records
	 * detail : detail for one record
	 * create : add a new item
	 * update : edit a record by its id
	 * delete : delete a record by its id
	 * 
	 * @var 	array
	 */
	protected $_actions = array(
		'index'		=> 'list',
		'detail'	=> 'detail',
		'create'	=> 'create',
		'update'	=> 'update',
		'delete'	=> 'delete',
	);
	
	/**
	 * The controller redirects the user to an action after performang an action
	 * eg. got to the overview (list) after updating (update) an existing record
	 * 
	 * By this array you can control what action will be redirect to after 
	 * completing another action.
	 * 
	 * The array contains:
	 * performand action => goto action
	 */
	protected $_redirectAfterActions = array(
		'create'	=> 'list',
		'update'	=> 'list',
		'delete'	=> 'list',
	);
	
	/**
	 * Mapper
	 * 
	 * @var		Zend_Db_Table
	 */
	protected $_mapper;
	
	/**
	 * Mapper Name
	 * 
	 * @var		string
	 */
	protected $_mapperName;
	
	/**
	 * Form names
	 * 
	 * @var		array
	 */
	protected $_formNames = array(
		'list'		=> null,
		'create'	=> null,
		'update'	=> null,
		'delete'	=> null,
	);
	
	/**
	 * Base url to use with this controller
	 * 
	 * @var		string
	 */
	protected $_baseUrl;
	
	/**
	 * Use flash messenger for messages
	 * 
	 * @var		bool
	 */
	protected $_flashMessengerOn = true;
	
	/**
	 * Flash messenger possible messages
	 * 
	 * @var		array
	 */
	protected $_flashMessages = array(
		'listErrorForm'		=> 'core.crud.msg.list.error.form',
		'createOk'			=> 'core.crud.msg.create.ok',
		'createErrorForm'	=> 'core.crud.msg.create.error.form',
		'createErrorSave'	=> 'core.crud.msg.create.error.save',
		'updateOk'			=> 'core.crud.msg.update.ok',
		'updateErrorForm'	=> 'core.crud.msg.update.error.form',
		'updateErrorSave'	=> 'core.crud.msg.update.error.save',
		'deleteOk'			=> 'core.crud.msg.delete.ok',
		'deleteErrorForm'	=> 'core.crud.msg.delete.error.form',
		'deleteErrorSave'	=> 'core.crud.msg.delete.error.save',
	);
	
	
	/**
	 * List action
	 */
	public function _listAction()
	{
		// assign basic urls
		$this->_assignActionUrlsToView();
		
		// defaults
		$form = false;
		$this->view->rows = new Zend_Db_Table_Rowset(array());
		
		// check if we have a request
		$form = $this->getForm('list');
		if($form)
		{
			$this->view->form = $form;
			$form->setMethod('get')
				 ->setAction($this->_getUrlByAction('list'));
			
			$request = $this->getRequest();
			/* @var	$request Zend_Controller_Request_Http */
			if(!$form->isValid($request->getQuery()))
			{
				$this->_addFlashMessage('listErrorForm');
				return;
			}
		}
		
		// get a list of data
		$list = $this->_getList($form);
		
		// Check if the list is an paginator
		if(!($list instanceof Zend_Paginator))
		{
			$paginator = Zend_Paginator::factory($list);
		}
		
		// set the current page
		$paginator->setCurrentPageNumber($this->_getParam('page'));
		
		$this->view->hasData = (bool)$list->count();
		$this->view->rows = $paginator;
		
		if(isset($request))
		{
			$this->view->query = $request->getQuery();
		}
	}
	
	/**
	 * Detail action
	 */
	public function _detailAction()
	{
		// get the row we need to update
		$row = $this->_getRowByRequest();
		
		// check if we found the row
		if(!$row)
		{
			$this->_forwardTo('list');
			return;
		}
		
		// assign the row to the view
		$this->view->row = $row;
		
		// assign basic urls
		$this->_assignActionUrlsToView();
	}
	
	/**
	 * Add action
	 */
	public function _createAction()
	{
		// assign basic urls
		$this->_assignActionUrlsToView();
		
		$form = $this->getForm('create');
		$form->setAction($this->_getUrlByAction('create'));
		$this->view->form = $form;
		
		$request = $this->getRequest();
		
		// check if is post
		if(!$request->isPost())
		{
			return;
		}
		
		// check if valid
		if(!$form->isValid($request->getPost()))
		{
			$this->_addFlashMessage('createErrorForm');
			return;
		}
		
		// save the data
		try
		{
			$row = $this->_prepareCreateRow($form);
			if(!$this->_saveCreate($row, $form))
			{
				$this->_addFlashMessage('createErrorSave');
				return;
			}
			$this->_postCreateRow($row, $form);
			
			$this->_addFlashMessage('createOk');
			
			$this->_forwardAfter('create');
			return;
		}
		catch (Exception $e)
		{
			$this->_addFlashMessage('createErrorSave');
		}
		
		return;
	}
	
	/**
	 * Edit action
	 */
	public function _updateAction()
	{
		// get the row we need to update
		$row = $this->_getRowByRequest();
		
		// check if we found the row
		if(!$row)
		{
			$this->_forwardTo('list');
			return;
		}
		
		// assign the row to the view
		$this->view->row = $row;
		
		// assign basic urls
		$this->_assignActionUrlsToView();
		
		// get the request
		$request = $this->getRequest();
		
		// get the form
		$form = $this->getForm('update');
		$form->setAction($this->_getUrlByAction('update') . '/' . $row->id);
		$this->view->form = $form;
		
		// check if post
		if(!$request->isPost())
		{
			$this->_populateForm($form, $row);
			return;
		}
		
		// check if is valid post
		if(!$form->isValid($request->getPost()))
		{
			$this->_addFlashMessage('updateErrorForm');
			return;
		}
		
		// save the data
		try
		{
			$this->_prepareUpdateRow($row, $form);
			if(!$this->_saveUpdate($row, $form))
			{
				$this->_addFlashMessage('updateErrorSave');
				return;
			}
			$this->_postUpdateRow($row, $form);
			
			$this->_addFlashMessage('updateOk');
			
			$this->_forwardAfter('update');
			return;
		}
		catch (Exception $e)
		{
			Zend_Debug::dump($e->getMessage());
			die;
			$this->_addFlashMessage('updateErrorSave');
		}
		
		return;
	}
	
	/**
	 * Delete action
	 */
	public function _deleteAction()
	{
		// get the row we need to update
		$row = $this->_getRowByRequest();
		
		// check if we found the row
		if(!$row)
		{
			$this->_forwardTo('list');
			return;
		}
		
		// assign the row to the view
		$this->view->row = $row;
		
		// assign basic urls
		$this->_assignActionUrlsToView();
		
		// get the request
		$request = $this->getRequest();
		
		// get the form
		$form = $this->getForm('delete');
		$form->setAction($this->_getUrlByAction('delete') . '/' . $row->id);
		$this->view->form = $form;
		
		// check if post
		if(!$request->isPost())
		{
			$this->_populateForm($form, $row);
			return;
		}
		
		// check if is valid post
		if(!$form->isValid($request->getPost()))
		{
			$this->_addFlashMessage('deleteErrorForm');
			return;
		}
		
		// save the data
		try
		{
			$this->_prepareDeleteRow($form, $row);
			if(!$this->_delete($row))
			{
				$this->_addFlashMessage('deleteErrorSave');
				return;
			}
			
			$this->_addFlashMessage('deleteOk');
			
			$this->_forwardAfter('delete');
			return;
		}
		catch (Exception $e)
		{
			$this->_addFlashMessage('deleteErrorSave');
		}
		
		return;
	}
	
	
	/**
	 * Method to get mapper
	 * 
	 * @param	void
	 * @return	Zend_Db_Table_Abstract
	 */
	public function getMapper()
	{
		if(!$this->_mapper)
		{
			$this->_mapper = new $this->_mapperName;
		}
		
		return $this->_mapper;
	}
	
	/**
	 * Method to set mapper
	 * 
	 * @param	Zend_Db_Table_Abstract
	 * @return	self
	 */
	public function setMapper($_mapper)
	{
		$this->_mapper = $_mapper;
		return $this;
	}
	
	/**
	 * Method to create the form object
	 * 
	 * @param	context
	 * @return	SG_Form
	 */
	public function getForm($_context)
	{
		if(!isset($this->_formNames[$_context]))
		{
			return false;
		}
		
		$form = new $this->_formNames[$_context](
			array('context' => $_context)
		);
		
		return $form;
	}
	
	
	/**
	 * Auto actions based on the $_actions array
	 * 
	 * @param	requested action
	 * @param	parameters
	 * @return	void
	 */
	public function __call($_methodName, $_arguments)
	{
		if('Action' == substr($_methodName, -6))
		{
			$action = substr($_methodName, 0, strlen($_methodName) - 6);
            
			if(isset($this->_actions[$action]))
			{
				$method = '_' 
					. $this->_actions[$action] 
					. 'Action';
					
				$this->$method();
				return;
			}
		}
		
        throw new Zend_Controller_Action_Exception(
        	sprintf(
        		'Method "%s" does not exist and was not trapped in __call()', 
        		$_methodName
        	)
        	, 500
        );
	}
	
	
	/**
	 * Extract the form data
	 * 
	 * @param	Zend_Form
	 * @return	array
	 */
	protected function _extractFormData(Zend_Form $_form)
	{
		return $_form->getValues();
	}
	
	/**
	 * Populate form with object data
	 * 
	 * @param	Zend_Form
	 * @param	Zend_Db_Table_Row_Abstract
	 * @return	void
	 */
	protected function _populateForm(Zend_Form $_form, Zend_Db_Table_Row_Abstract $_row)
	{
		$_form->populate($_row->toArray());
	}
	
	/**
	 * Get a list of recrods
	 * 
	 * @param	Zend_Form
	 * @return	Zend_Db_Table_Rowset
	 */
	protected function _getList($_form = false)
	{
		return $this->getMapper()->fetchAll();
	}
	
	/**
	 * Get an existing row by the request
	 * Default fetch a row by the request id
	 * 
	 * @param	void
	 * @return	Zend_Db_Table_Row_Abstract
	 */
	protected function _getRowByRequest()
	{
		$id = $this->getRequest()->getParam('id', null);
		
		if(is_null($id))
		{
			return false;
		}
		
		return $this->getMapper()->find($id)->current();	
	}
	
	/**
	 * Prepare the data for saving a new record
	 * 
	 * @param	Zend_Form
	 * @return	Zend_Db_Table_Row_Abstract
	 */
	protected function _prepareCreateRow(Zend_Form $_form)
	{
		$row = $this->getMapper()->createRow($_form->getValues());
		return $row;
	}
	
	/**
	 * Post create row
	 * 
	 * @param	Zend_Db_Table_Row_Abstract
	 * @param	Zend_Form
	 * @return	void
	 */
	protected function _postCreateRow(
		Zend_Db_Table_Row_Abstract $_row,
		Zend_Form $_form
	)
	{
		
	}
	
	/**
	 * Prepare the row for saving updated data
	 * 
	 * @param	Zend_Db_Table_Row_Abstract
	 * @param	Zend_Form
	 * @return	void
	 */
	protected function _prepareUpdateRow(
		Zend_Db_Table_Row_Abstract $_row,
		Zend_Form $_form
	)
	{
		$_row->setFromArray($_form->getValues());
	}
	
	/**
	 * Post update row
	 * 
	 * @param	Zend_Db_Table_Row_Abstract
	 * @param	Zend_Form
	 * @return	void
	 */
	protected function _postUpdateRow(
		Zend_Db_Table_Row_Abstract $_row,
		Zend_Form $_form
	)
	{
		
	}
	
	/**
	 * Prepare the data for deleting
	 * 
	 * @param	Zend_Form
	 * @param	Zend_Db_Table_Row_Abstract
	 * @return	void
	 */
	protected function _prepareDeleteRow(
		Zend_Form $_form, 
		Zend_Db_Table_Row_Abstract $_row
	)
	{
		
	}
		
	/**
	 * Save a new record
	 * 
	 * @param	Zend_Db_Table_Row_Abstract
	 * @param	Zend_Form
	 * @return	bool	success
	 */
	protected function _saveCreate(
		Zend_Db_Table_Row_Abstract $_row, 
		Zend_Form $_form
	)
	{
		return (bool)$_row->save();
	}
	
	/**
	 * Save an updated record
	 * 
	 * @param	Zend_Db_Table_Row_Abstract
	 * @param	Zend_Form
	 * @return	bool	success
	 */
	protected function _saveUpdate(
		Zend_Db_Table_Row_Abstract $_row, 
		Zend_Form $_form
	)
	{
		return (bool)$_row->save();
	}
	
	/**
	 * Delete a record
	 * 
	 * @param	Zend_Db_Table_Row_Abstract
	 * @return	bool	success
	 */
	protected function _delete(Zend_Db_Table_Row_Abstract $_row)
	{
		return (bool)$_row->delete();
	}
	
	
	/**
	 * Flash messages
	 * 
	 * @param	string	message
	 * @return	void
	 */
	protected function _addFlashMessage($_message)
	{
		if(!$this->_flashMessengerOn)
		{
			return;
		}
		
		if(isset($this->_flashMessages[$_message]))
		{
			$_message = $this->_flashMessages[$_message];
		}
		
		$this->_helper->FlashMessenger($_message);
	}
	
	
	/**
	 * Assign actions urls to view
	 * 
	 * @param	void
	 * @return	void
	 */
	protected function _assignActionUrlsToView()
	{
		$this->view->actionBase = $this->_getUrlByAction();
		$this->view->actionList = $this->_getUrlByAction('list');
		$this->view->actionCreate = $this->_getUrlByAction('create');
		$this->view->actionUpdate = $this->_getUrlByAction('update');
		$this->view->actionDelete = $this->_getUrlByAction('delete');
	}
	
	/**
	 * Forward function
	 * 
	 * @param	string action to forward to
	 * @return	void
	 */
	protected function _forwardTo($_nextAction)
	{
		$this->_redirect($this->_getUrlByAction($_nextAction));
	}
	
	/**
	 * Forward to after an action
	 * 
	 * @param	string	last performand action
	 * @return	void
	 */
	protected function _forwardAfter($_lastAction)
	{
		if(!isset($this->_redirectAfterActions[$_lastAction]))
		{
			throw new Zend_Controller_Action_Exception(
				sprintf(
					'Next action "%s" does not exist', $_lastAction)
			);
		}
		
		$this->_forwardTo($this->_redirectAfterActions[$_lastAction]);
	}
	
	/**
	 * Get an url by the given action
	 * This is used to map the dynamic actions by their default alias
	 * 
	 * @param	string
	 * @return	string
	 */
	protected function _getUrlByAction($_action = null)
	{
		$routeName = $this->getFrontController()
							->getRouter()
							->getCurrentRouteName();
							
		$request = $this->getRequest();
		
		// define the action
		if(is_null($_action))
		{
			$action = $request->getActionName();
		}
		elseif(in_array($_action, $this->_actions))
		{
			$action = array_search($_action, $this->_actions);
		}
		else
		{
			$action = $_action;
		}
		
		// check for action = index
		if($action == 'index')
		{
			$action = null;
		}
		
		return $this->getFrontController()->getRouter()->assemble(
			array(
				'controller' => $request->getControllerName(),
				'action'	 => $action,
				'module'	 => $request->getModuleName(),
			), 
			$routeName, 
			true
		);
	}
}