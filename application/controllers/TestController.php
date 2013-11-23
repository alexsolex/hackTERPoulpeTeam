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
        
//        $t = new Application_Model_DbTable_Gain();
//        $this->view->gain = $t->fetchAll()->toArray();
//        
//        $t = new Application_Model_DbTable_Gare();
//        $this->view->gare = $t->fetchAll()->toArray();
//        
//        $t = new Application_Model_DbTable_Partenaire();
//        $this->view->partenaire = $t->fetchAll()->toArray();
//        
//        $t = new Application_Model_DbTable_Participant();
//        $this->view->participant = $t->fetchAll()->toArray();
//        
//        $t = new Application_Model_DbTable_Participer();
//        $this->view->participer = $t->fetchAll()->toArray();
//        
//        $t = new Application_Model_DbTable_Question();
//        $this->view->question = $t->fetchAll()->toArray();
//        
//        $t = new Application_Model_DbTable_Quizz();
//        $this->view->quizz = $t->fetchAll()->toArray();
//        
//        $t = new Application_Model_DbTable_Situer();
//        $this->view->situer = $t->fetchAll()->toArray();
        
        // exemple wikipedia
        
                //$fql_query_url = 'http://fr.wikipedia.org/w/api.php?format=json&action=query&titles=Orchies&prop=revisions&rvprop=content';
        
//
//        $fql_query_result = file_get_contents($fql_query_url);
//        $fql_query_obj = json_decode($fql_query_result, true);
//        
//        $this->view->json = $fql_query_obj;
//    
        $ville = 'Lille';
	
	$queryUrl = 'http://api.openweathermap.org/data/2.5/weather?q=' . $ville . '&units=metric&lang=fr';
	
	$result = file_get_contents($queryUrl);
	$tabResult = json_decode($result, true);
        
        $this->view->result = $tabResult;

    }
    
public function listerFacebook() {

        //requete FQL
        

    }
}

