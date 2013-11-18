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
}

