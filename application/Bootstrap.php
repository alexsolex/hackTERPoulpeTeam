<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initDoctype()
    {
         $this->bootstrap('view');
         $view = $this->getResource('view');
         $view->doctype('HTML5');
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
    
    protected function _initTimezone()
    {
        date_default_timezone_set('Europe/Paris');
    }

    protected function _initRoutes()
    {
        $front = Zend_Controller_Front::getInstance();
        $router = $front->getRouter();

        $route = new Zend_Controller_Router_Route('login/:provider',
                                                  array(
                                                  'module' => 'mobi',
                                                  'controller' => 'user',
                                                  'action' => 'login'
                                                  ));
        $router->addRoute('login/:provider', $route);
         $route = new Zend_Controller_Router_Route_Static('login',
                                                         array(
                                                             'module' => 'mobi',
                                                         'controller' => 'user',
                                                         'action' => 'login'
                                                         ));
        $router->addRoute('login', $route);

        $route = new Zend_Controller_Router_Route_Static('logout',
                                                         array(
                                                             'module' => 'mobi',
                                                         'controller' => 'user',
                                                         'action' => 'logout'
                                                         ));
        $router->addRoute('logout', $route);
    }
}

