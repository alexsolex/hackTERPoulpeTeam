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

}

