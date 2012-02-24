<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Loads and stores the config in the registry
     * 
     */
    protected function _initConfigs()
    {
        $registry = Zend_Registry::getInstance();
      
        $configs = array('config');
      
        foreach($configs AS $name) {
            $config = new Zend_Config_Ini(
                APPLICATION_PATH . '/configs/' . $name . '.ini',
                APPLICATION_ENV
            );
            $registry->set('config.' . $name, $config);
        }
    }
    
    /**
     * Init the database
     * 
     * Combines the application.ini & config.ini database settings
     */
    protected function _initDb()
    {
        $this->bootstrap('configs');
        
        // combine application.ini & config.ini database settings
        $options = $this->getOptions();
        $dbSettings = $options['resources']['db'];
        $dbParams = Zend_Registry::get('config.config')->db->toArray();
        $dbSettings['params'] = array_merge($dbSettings['params'], $dbParams);
        
        $db = Zend_Db::factory($dbSettings['adapter'], $dbSettings['params']);
        if(!empty($dbSettings['isDefaultTableAdapter'])
            && (bool)$dbSettings['isDefaultTableAdapter']
        ) {
            Zend_Db_Table::setDefaultAdapter($db);
        }
    }
    
    /**
     * Init the log to database
     */
    protected function _initLog()
    {
        // make sure the db bootstrap is done
        $this->bootstrap('db');
        
        // add the logger
        $writer = new SG_Log_Writer_Db();
        $logger = new Zend_Log($writer);
        $logger->registerErrorHandler();
        Zend_Registry::set('SG_Logger', $logger);
    }
    
    /**
     * Init the db metadata caching
     */
    protected function _initDbMetaCache()
    {
        // make sure the db bootstrap is done
        $this->bootstrap('db');
        
        // First, set up the Cache
        $frontendOptions = array(
            'automatic_serialization' => true
        );
        $backendOptions  = array(
          'cache_dir' => APPLICATION_PATH . '/../data/cache'
        );
        $cache = Zend_Cache::factory(
            'Core', 'File', $frontendOptions, $backendOptions
        );
        // set the default cache
        Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
    }
    
    /**
     * Init the View Helpers
     * 
     * @TODO this should be loaded trough the application.ini settings!
     */
    protected function _initViewHelpers()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        
        $view->addHelperPath('TB/View/Helper','TB_View_Helper');
        $view->addHelperPath('SG/View/Helper','SG_View_Helper');

        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        Zend_View_Helper_PaginationControl::setDefaultViewPartial(array(
          '/partials/pagination_control.phtml',
          'default'
        ));
    }
    
    /**
     * Init the navigation
     * 
     * Creates the navigation from a config file
     * 
     * @todo Menu should come from the database!
     */
    protected function _initNavigation()
    {
        $this->bootstrap('layout');
        $this->bootstrap('translate');
        
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        
        // get the platform navigation
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/navigation.ini');
        
        // get the application specific navigation
        $appSpecificNavFile = APPLICATION_PATH . '/configs/navigation-app.ini';
        if(file_exists($appSpecificNavFile)) {
            $appNav = new Zend_Config_Ini($appSpecificNavFile);
            $config = new Zend_Config(array_merge_recursive(
                $config->toArray(), $appNav->toArray()
            ));
        }
        
        // add the navigation to the View 
        $navigation = new Zend_Navigation($config);
        $view->navigation($navigation);
    }
}

