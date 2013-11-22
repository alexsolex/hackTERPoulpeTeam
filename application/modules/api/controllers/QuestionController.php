<?php

class Api_QuestionController extends Zend_Controller_Action {

    const DUREE_QUESTION = 60;//3minutes
    
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

        $auth = TBS_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_response->setHttpResponseCode(300);
            $this->view->message("non connecté");
            return;
        }
        $this->view->providers = $auth->getIdentity()->toArray();
    }
    
    /*
     * Retourne la Question, le sponsor, les propositions
     */
    public function obtenirAction() {
        // récupère la gare de l'utilisateur
        //$idGare = $this->getIdGare();
        $tvs = $this->_request->getParam('TVS');
        
        // parametre 
        // TODO peut être : un paramètre pour indiquer si la requête provient d'un écran ou d'un mobile
        $isEcran = array_search("ecran", $this->_request->getParams());
        
                
        //récupère le quizz en cours
        $leQuizz = $this->getCurrentQuizzGare($tvs);
       
        
        //Termine le quizz si celui ci est expiré
        if (!is_null($leQuizz) && !$this->estActif($leQuizz) ) {
            //$this->view->dateDebut = $dateDebut;
            $this->view->message = "quizz expiré";
            $this->terminerQuizz($leQuizz->idQuizz);
            //return;
        
        }
        //Si le quizz est terminé ou plus actif (expiration
        if (is_null($leQuizz) || !$this->estActif($leQuizz) ) {
            //  - récupérer le prochain quizz dans la liste
            $t = new Application_Model_DbTable_Quizz();
            $nouveauQuizz = $t->getNewQuizz($tvs)->current();
            
            
            //  - si il n'y a pas de nouveauQuizz, alors lancer un nettoyage de la table
            //      (supprimer les datesDebut et dateFin pour tous les quizz de la gare)
            if (is_null($nouveauQuizz)) {
                //Nettoye les quizz de la gare (dateDebut et dateFin à null)
                $t->restartQuizzList($tvs);
                //obtient ensuite le premier quizz
                $leQuizz = $t->getNewQuizz($tvs)->current();
                
            }
            else {
                $leQuizz = $nouveauQuizz;
            }
            
            $leQuizz->dateDebut = Zend_Date::now()->toString('yyyy-MM-dd HH:mm:ss');
            
            $this->demarrerQuizz($leQuizz->idQuizz);
            
        }

        
        
        //
        // présentation des données
        //
        
        // récupère la question en cours
        $question =  $this->getQuestion($leQuizz);
        $leSponsor = $this->getSponsor($leQuizz);
        
        $this->view->question = $question;
        $this->view->sponsor = $leSponsor;
        $this->view->tvs = $tvs;
        $this->view->idq = $leQuizz->idQuizz;
        
    }
    
    /*
     * Action pour prendre en compte la réponse d'un utilisateur
     */
    public function repondreAction() {
        $tvs = $this->_request->getParam('TVS');
        $idQuizzReponse = $this->_request->getParam('idq');
        
        //récupérer le quizz de l'ID
        $t = new Application_Model_DbTable_Quizz();
        $quizz = $t->fetchRow($t->select()->where('idQuizz = ?', $idQuizzReponse));
        
        //récupérer la gare de TVS
        $tGare = new Application_Model_DbTable_Gare();
        $gare = $tGare->fetchRow($tGare->select()->where('tvs = ?', $tvs));
        
        //$this->view->test = $quizz->findParentRow("Application_Model_DbTable_Gare")->tvs;
        
        if ($quizz->idGare != $gare->idGare) {
            $this->_response->setHttpResponseCode(400);
            $this->view->message = "Cette gare ne possède pas ce quizz";
            return; 
        }
        
        $leQuizzGare = $this->getCurrentQuizzGare($tvs);
        
        if (is_null($leQuizzGare)) {    
            $this->_response->setHttpResponseCode (400);
            $this->view->message = "Il n'y a pas de quizz actuellement dans cette gare.";
            return;
        }
        
        if ($leQuizzGare->idQuizz != $idQuizzReponse) {
            $this->_response->setHttpResponseCode(400);
            $this->view->message = "Ce quizz n'est plus actif";
            return; 
        }
        
        if (!$this->estActif($leQuizzGare)) {
            $this->_response->setHttpResponseCode(400);
            $this->view->message = "Trop tard ! Ce quizz est terminé";
            return; 
        }
        
        //vérification de la réponse
        $bienRepondu = false;
        $laReponse = $this->_request->getParam('reponse',"");
        $bonneReponse = $leQuizzGare->reponse;
        if ($laReponse == $bonneReponse) {
            //bonne réponse !!
            $bienRepondu = true;
            //est-ce la première bonne réponse
            if ( is_null($leQuizzGare->idParticipant) ) {
                $quizz->idParticipant = 1;//TODO le vainqueur n'est pas statique !!
            }
            $this->terminerQuizz($leQuizzGare->idQuizz,$bienRepondu);
        }
        
        $this->view->quizz= $leQuizzGare->toArray();
        
        $this->view->reponseOK = $bienRepondu;
        $this->view->solution = $bonneReponse;
        
        
        
    }

    
    
    /*
     * indique si l'objet quizz est actif en prenant en compte la durée de la question
     */
    public static function estActif($leQuizz) {
        $dateDebut = new Zend_Date( $leQuizz->dateDebut );//,'dd/MM/yyyy HH:mm:ss' );
        if ($dateDebut->addSecond(self::DUREE_QUESTION)->compare(Zend_Date::now())<0){
            return false;
        }
        else {
            return true;
        }
    }
    
    
    /*
     * Obtient l'objet quizz en cours (date début sans date fin)
     */
    public static function getCurrentQuizzGare($tvs) {
        
        $tableQuizz = new Application_Model_DbTable_Quizz();
        $quizzRowset = $tableQuizz->getCurrentQuizz($tvs);
        if ($quizzRowset->count()<1) {
            return null;
        }
        $leQuizz = $quizzRowset->current();
        return $leQuizz;
    }

    /*
     * configure le quizz (id) avec la date de fin
     */
    public static function terminerQuizz($idQuizz,$estRepondu = false) {
        //TODO : insérer la date 
        $t = new Application_Model_DbTable_Quizz();
        $quizz = $t->fetchRow($t->select()->where('idQuizz = ?', $idQuizz));
        $quizz->dateFin = Zend_Date::now()->toString('yyyy-MM-dd HH:mm:ss');
        $quizz->estRepondu = $estRepondu;
        $quizz->save();
    }
    
    /*
     * configure le quizz (id) avec la date début 
     */
    public static function demarrerQuizz($idQuizz) {
        $t = new Application_Model_DbTable_Quizz();
        $quizz = $t->fetchRow($t->select()->where('idQuizz = ?', $idQuizz));
        $quizz->dateDebut = Zend_Date::now()->toString('yyyy-MM-dd HH:mm:ss');
        $quizz->save();
    }
    
    /*
     * Prépare la question de l'objet quizz pour affichage
     */
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

    /*
     * prépare le sponsor de l'objet quizz pour affichage
     */
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
    
    public static function getSolution($leQuizz) {
        return $leQuizz->reponse;
    }
    
}
