<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
   
    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('HTML5');
        $view->addHelperPath(APPLICATION_PATH . '/views/helpers');
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');
    }
    
    protected function _initConfig()
    {
        Zend_Registry::set('config', $this->getOptions());
    }
    protected function _initAppAutoload()
    {
        $autoloader = new Zend_Application_Module_Autoloader(array(
           'namespace' => '',
            'basePath'  => dirname(__FILE__),
        ));
        return $autoloader;
    }

    protected function _initLayoutHelper()
    {
        $this->bootstrap('frontController');
        $layout = Zend_Controller_Action_HelperBroker::addHelper(
            new Application_View_Helper_LayoutLoader());
    }
    protected function _initHelperPath()
    {
        Zend_Controller_Action_HelperBroker::addPath(
                APPLICATION_PATH . '/controllers/helpers',
                'Application_Controller_Action_Helper_');
    }
    /**
     * initialisation de la journalisation
     */
    protected function _initLogging()
    {
        $logger = new Zend_Log();
        // récupérer et filtrer sur le niveau de log
        $optionLevel = (int) $this->_options["logging"]["level"];
        $filter = new Zend_Log_Filter_Priority($optionLevel);
        $logger->addFilter($filter);
        // ajouter un rédacteur qui écrit dans le fichier défini
        $optionPath = $this->_options["logging"]["filename"];
        $writer = new Zend_Log_Writer_Stream($optionPath);
        $logger->addWriter($writer);
        Zend_Registry::set("cml_logger", $logger);
    }
    
     protected function _initAcl()
    {
        // plugin Acl/Auth
        $acl_ini = APPLICATION_PATH . '/configs/acl.ini' ;
        $acl     = new Application_Plugin_AclIni($acl_ini);
        Zend_Controller_Front::getInstance()->registerPlugin(new Application_Plugin_AuthPlugin($acl));
        Zend_Registry::set('Zend_Acl', $acl);
    }
    
    protected function _initTimezone()
    {
        date_default_timezone_set('Europe/Paris');
    }

    
    protected function _initPlugins()
    {
        $this->bootstrap('db');
        $this->_db = $this->getResource('db');
        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(
            new Application_Plugin_ErrorRoutingPlugin()
        );
    }
    
    protected function _initRoutes()
    {
        $front = Zend_Controller_Front::getInstance();
        $router = $front->getRouter();

        $route = new Zend_Controller_Router_Route('login/:provider',
                                                  array(
                                                  'controller' => 'utilisateur',
                                                  'action' => 'login'
                                                  ));
        $router->addRoute('login/:provider', $route);
         $route = new Zend_Controller_Router_Route_Static('login',
                                                         array(
                                                         'controller' => 'utilisateur',
                                                         'action' => 'login'
                                                         ));
        $router->addRoute('login', $route);

        $route = new Zend_Controller_Router_Route_Static('logout',
                                                         array(
                                                         'controller' => 'utilisateur',
                                                         'action' => 'logout'
                                                         ));
        $router->addRoute('logout', $route);
    }
//    protected function _initRouter() {
//        $front = $this->bootstrap('FrontController')->getResource('FrontController');
//        $router = $front->getRouter();
//        
//        //le module API est REST
//        $restRoute = new Zend_Rest_Route($front, array(), array('api',));
//        $router->addRoute('rest', $restRoute);
//        
//    }
    
    /*protected function _initZFDebug()
    {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('ZFDebug');

        $options = array(
            'plugins' => array('Variables', 
                               'File' => array('base_path' => '/path/to/project'),
                               'Memory', 
                               'Time', 
                               'Registry', 
                               'Exception')
        );

        # Instantiate the database adapter and setup the plugin.
        # Alternatively just add the plugin like above and rely on the autodiscovery feature.
        if ($this->hasPluginResource('db')) {
            $this->bootstrap('db');
            $db = $this->getPluginResource('db')->getDbAdapter();
            $options['plugins']['Database']['adapter'] = $db;
        }

        # Setup the cache plugin
        if ($this->hasPluginResource('cache')) {
            $this->bootstrap('cache');
            $cache = $this-getPluginResource('cache')->getDbAdapter();
            $options['plugins']['Cache']['backend'] = $cache->getBackend();
        }

        $debug = new ZFDebug_Controller_Plugin_Debug($options);

        $this->bootstrap('frontController');
        $frontController = $this->getResource('frontController');
        $frontController->registerPlugin($debug);
    }*/
    
}

