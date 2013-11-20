<?php

class Api_QuestionController extends Zend_Controller_Action {

    public function init() {
        //Context : on le force en json
        $this->_request->setParam('format', 'json');
        
        $contextSwitch = $this->_helper->contextSwitch();
        $contextSwitch->addActionContext('index', 'json')
                      ->addActionContext('error','json')
                      ->addActionContext('repondre','json')
                      ->addActionContext('obtenir','json')
                      
                      ->initContext();
    }

    public function indexAction() {
//        $idGare = $this->getIdGare();
//        $question =  $this->getQuestion($idGare);
//        $arrayQuestion = $question[1];
//        $idQuestion = $question[0];
//        $this->view->question = $arrayQuestion;
//        $leSponsor = $this->getSponsor($idQuestion);
//        $this->view->sponsor = $leSponsor[1];
        
    }
    
    /*
     * Retourne la Question, le sponsor, les propositions
     */
    public function obtenirAction() {
        // récupère la gare de l'utilisateur
        //$idGare = $this->getIdGare();
        $tvs = $this->_request->getParam('TVS');
        
        $tableQuizz = new Application_Model_DbTable_Quizz();
        $quizzRowset = $tableQuizz->getQuizz($tvs);
        if ($quizzRowset->count()<1) {
            $this->_response->setHttpResponseCode (400);
            return;
        }
        $leQuizz = $quizzRowset->current();
        $this->view->quizz = $leQuizz->toArray();

        // récupère la question en cours
        $question =  $this->getQuestion($leQuizz);
        
        //idQuestion
        $idQuestion = $leQuizz->idQuestion;
        
        $this->view->question = $question;
        $leSponsor = $this->getSponsor($leQuizz);
        $this->view->sponsor = $leSponsor;
        $this->view->tvs = $tvs;
        
    }
    
    public function repondreAction() {
        $bienRepondu = false;
        //récupérer les params :
        //  - l'ID question
        //  - le num réponse
        $laReponse = $this->_request->getParam('reponse',"");
        $bonneReponse = $this->getSolution();
        if ($laReponse == $bonneReponse) {
            $bienRepondu = true;
        }
        $this->view->reponseOK = $bienRepondu;
        $this->view->solution = $bonneReponse;
        
        
        
    }

    
    
    public static function getSolution() {
        return "Orchies";
    }
    
    public static function getQuestion($leQuizz) {
        //TODO $idGare;
        $propositions = array( $leQuizz->reponse, $leQuizz->erreur1, $leQuizz->erreur2,$leQuizz->erreur3 );
        //shuffle($propositions);
        return array (
                'question' => $leQuizz->libelleQuestion,//"Une commune française, située dans le département du Nord (59) en région Nord-Pas-de-Calais. Le nom jeté des habitants est les pourchots1, signifiant « porc » en picard.",
                'choix1' => $propositions[0],//"GareA",
                'choix2' => $propositions[1],//"Orchies",
                'choix3' => $propositions[2],//"GareC",
                'choix4' => $propositions[3]//"GareD",
            );
    }

    public static function getSponsor($leQuizz) {
        return array (
                'nom' => $leQuizz->nomPartenaire,
                'FB' => $leQuizz->fbPartenaire,
                'twitter' => $leQuizz->twPartenaire,
                'google' => $leQuizz->gooPartenaire,
                'url' => $leQuizz->urlPartenaire,
                'logo' => $leQuizz->logoPartenaire
            );
    }

    public static function getIdGare() {
        return "LEW";
    }
}
