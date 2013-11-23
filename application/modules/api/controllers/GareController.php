<?php

class Api_GareController extends Zend_Controller_Action {

    public function init() {
        //Context : on le force en json
        $this->_request->setParam('format', 'json');
        
        $contextSwitch = $this->_helper->contextSwitch();
        $contextSwitch->addActionContext('index', 'json')
                      ->addActionContext('lister', 'json')
                      ->initContext();
    }

    public function indexAction() {
        $this->_response->setHttpResponseCode(400);
        $this->view->message = "il n'y a rien à voir ici";
    }
    
    public function listerAction() {
        $tableGare = new Application_Model_DbTable_Gare();
        $recherche = $this->_request->getParam("q");
        
        $this->view->lesGares = $tableGare->fetchAll()->toArray();
    }

    
    
}
