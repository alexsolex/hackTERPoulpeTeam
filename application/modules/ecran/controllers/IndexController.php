<?php

class Ecran_IndexController extends Zend_Controller_Action
{
    
    public function init()
    {
        
   
    }

    public function indexAction()
    {
        $this->view->nomVariable = "valeur de la variable passée à la vue";

    }

}

