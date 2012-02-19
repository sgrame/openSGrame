<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Init the application constants
     */
    protected function __initContstants()
    {
        // Define the public base url
        $url = array();
        $url['protocol'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on')
          ? 'https://'
          : 'http://';
        $url['domain'] = rtrim($_SERVER['HTTP_HOST'], '/');
        
        defined('APPLICATION_URL')
        or define(
          'APPLICATION_URL', 
          $url['protocol'] . $url['domain']
        );
    }
    
    /**
     * Loads and stores the config in the registry
     * 
     */
    protected function __initConfigs()
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
     * Init the translation
     * 
     */
    protected function __initTranslation()
    {
        // load the language
        $translate = new Zend_Translate(
          'array', APPLICATION_PATH .'/../languages/nl.php', 'nl'
        );
        Zend_Registry::set('Zend_Translate', $translate);
    }
    
    /**
     * Init the locals
     */
    protected function __initLocals()
    {
        date_default_timezone_set('Europe/Brussels');
        $locale = new Zend_Locale('nl_BE'); 
        Zend_Registry::set('Zend_Locale', $locale); 
    }
    
    /**
     * Init the log to database
     */
    protected function __initLog()
    {
        // make sure the db bootstrap is done
        $this->bootstrap('db');
        
        // add the logger
        $writer = new SG_Log_Writer_Db();
        $logger = new Zend_Log($writer);
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
        
        $view->addHelperPath('TB/View/Helper','TB_View_Helper');
        $view->addHelperPath('SG/View/Helper','SG_View_Helper');
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
        
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/navigation.ini');
        
        // add the main navigation 
        $navigation = new Zend_Navigation($config);
        $view->navigation($navigation);
    }

}

