<?php


class Api_UserController extends Zend_Controller_Action
{   
        

    public function init()
    {
        //Context : on le force en json
        $this->_request->setParam('format', 'json');
        
        $contextSwitch = $this->_helper->contextSwitch();
        $contextSwitch->addActionContext('index', 'json')
                      
                      ->initContext();
    }
    
    public function preDispatch() {

    }
    
        
    public function indexAction()
    {
      
    }

   
    
}

