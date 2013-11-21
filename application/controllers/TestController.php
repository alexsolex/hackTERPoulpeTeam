<?php

class TestController extends Zend_Controller_Action
{
   

    public function init() {
        //Context : on le force en json
        $this->_request->setParam('format', 'json');
        
        $contextSwitch = $this->_helper->contextSwitch();
        $contextSwitch->addActionContext('index', 'json')
                      
                      ->initContext();
    }

   
    public function indexAction()
    {
        
        $t = new Application_Model_DbTable_Gain();
        $this->view->gain = $t->fetchAll()->toArray();
        
        $t = new Application_Model_DbTable_Gare();
        $this->view->gare = $t->fetchAll()->toArray();
        
        $t = new Application_Model_DbTable_Partenaire();
        $this->view->partenaire = $t->fetchAll()->toArray();
        
        $t = new Application_Model_DbTable_Participant();
        $this->view->participant = $t->fetchAll()->toArray();
        
        $t = new Application_Model_DbTable_Participer();
        $this->view->participer = $t->fetchAll()->toArray();
        
        $t = new Application_Model_DbTable_Question();
        $this->view->question = $t->fetchAll()->toArray();
        
        $t = new Application_Model_DbTable_Quizz();
        $this->view->quizz = $t->fetchAll()->toArray();
        
        $t = new Application_Model_DbTable_Situer();
        $this->view->situer = $t->fetchAll()->toArray();
        
        
    }

}

