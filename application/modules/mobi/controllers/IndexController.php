<?php

class Mobi_IndexController extends Zend_Controller_Action
{

    
    public function init()
    {
        
   
    }

    public function indexAction()
    {
        $this->view->info = "Je suis l'action index du module Mobi";
    }

    
}

