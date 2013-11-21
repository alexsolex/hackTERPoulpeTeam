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
                      ->addActionContext('demarrer','json')
                
                      
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
        
        $leQuizz = $this->getQuizzGare($tvs);
        if (is_null($leQuizz)) {
            //echo $exc->getTraceAsString();
            $this->_response->setHttpResponseCode(404);
            $this->view->message = "Aucun Quizz trouvé";
            return;
            
        }

        //$this->view->quizz = $leQuizz->toArray();
        
        //teste si la question est expirée
        $dateDebut = new Zend_Date( $leQuizz->dateDebut ,'dd/MM/yyyy HH:mm:ss' );
        if ($dateDebut->addSecond(5*60)->compare(Zend_Date::now())<0) {
            $this->view->dateDebut = $dateDebut;
            $this->view->message = "quizz expiré";
            $this->terminerQuizz($leQuizz->idQuizz);
            return;
        }
        
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
        $tvs = $this->_request->getParam('TVS');
        try {
            $leQuizz = $this->getQuizzGare($tvs);
        } catch (Exception $exc) {
            //echo $exc->getTraceAsString();
            $this->_response->setHttpResponseCode (400);
            return;
        }

        
        $bienRepondu = false;
        //récupérer les params :
        //  - l'ID question
        //  - le num réponse
        $laReponse = $this->_request->getParam('reponse',"");
        $bonneReponse = $leQuizz->reponse;
        if ($laReponse == $bonneReponse) {
            $bienRepondu = true;
            $this->terminerQuizz($leQuizz->idQuizz);
        }
        $this->view->reponseOK = $bienRepondu;
        $this->view->solution = $bonneReponse;
        
        
        
    }

    /*
     * Action pour démarrer un nouveau quizz
     */
    public function demarrerAction() {
        //TODO :
        //  - s'assurer qu'un quizz n'est pas déjà en cours
        $tvs = $this->_request->getParam('TVS');
        $leQuizz = $this->getQuizzGare($tvs);
        if (!is_null($leQuizz)) {
            //echo $exc->getTraceAsString();
            $this->_response->setHttpResponseCode (400);
            $this->message = "Un quizz est déjà en cours";
            return;
        }
        //  - récupérer le prochain quizz dans la liste
        $t = new Application_Model_DbTable_Quizz();
        $nouveauQuizz = $t->getNewQuizz($tvs)->current();
        //$this->view->newQuizz = $nouveauQuizz->toArray();
        
        //  - si il n'y a pas de nouveauQuizz, alors lancer un nettoyage de la table
        //      (supprimer les datesDebut et dateFin pour tous les quizz de la gare)
        if (is_null($nouveauQuizz)) {
            //TODO
            $this->_response->setHttpResponseCode(500);
            $this->view->message = "Not implemented !";
            return;
        }
        //  - positionner la dateDebut
        $quizz = $t->fetchRow($t->select()->where('idQuizz = ?', $nouveauQuizz->idQuizz));
        $quizz->dateDebut = Zend_Date::now();
        $quizz->save();
        
        // récupère la question en cours
        $question =  $this->getQuestion($nouveauQuizz);
        
        $this->view->question = $question;
        $leSponsor = $this->getSponsor($nouveauQuizz);
        $this->view->sponsor = $leSponsor;
        $this->view->tvs = $tvs;
    }
    
    public static function getQuizzGare($tvs) {
        
        $tableQuizz = new Application_Model_DbTable_Quizz();
        $quizzRowset = $tableQuizz->getQuizz($tvs);
        if ($quizzRowset->count()<1) {
            //throw new Exception("Pas de quizz en cours");
            return null;
        }
        $leQuizz = $quizzRowset->current();
        return $leQuizz;
    }

    public static function terminerQuizz($idQuizz) {
        //TODO : insérer la date 
        $t = new Application_Model_DbTable_Quizz();
        $quizz = $t->fetchRow($t->select()->where('idQuizz = ?', $idQuizz));
        $quizz->dateFin = Zend_Date::now();
        $quizz->save();
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
