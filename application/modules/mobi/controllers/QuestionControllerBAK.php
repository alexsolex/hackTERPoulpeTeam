<?php //

class Mobi_QuestionController extends Zend_Controller_Action {

    public function init() {
        //Context : on le force en json
        //$this->_request->setParam('format', 'json');
        
        $contextSwitch = $this->_helper->contextSwitch();
        $contextSwitch->addActionContext('index', 'json')
                      ->addActionContext('error','json')
                      ->addActionContext('repondre','json')
                      ->addActionContext('obtenir','json')
                      
                      ->initContext();
    }

    public function indexAction() {
        $idGare = $this->getIdGare();
        $question =  $this->getQuestion($idGare);
        $arrayQuestion = $question[1];
        $idQuestion = $question[0];
        $this->view->question = $arrayQuestion;
        $leSponsor = $this->getSponsor($idQuestion);
        $this->view->sponsor = $leSponsor[1];
        
    }
    
    /*
     * Retourne la Question, le sponsor, les propositions
     */
    public function obtenirAction() {
        // récupère la gare de l'utilisateur
        $idGare = $this->getIdGare();
        // récupère la question en cours
        $question =  $this->getQuestion($idGare);
        
        //la question et propositions
        $arrayQuestion = $question[1];
        
        //idQuestion
        $idQuestion = $question[0];
        
        $this->view->question = $arrayQuestion;
        $leSponsor = $this->getSponsor($idQuestion);
        $this->view->sponsor = $leSponsor[1];
        
    }
    
    public function repondreAction() {
        $bienRepondu = false;
        //récupérer les params :
        //  - l'ID question
        //  - le num réponse
        $laReponse = $this->_request->getParam('reponse',"");
        
        if ($laReponse == getSolution()) {
            $bienRepondu = true;
        }
        $this->view->victoire = $victoire;
        $this->view->solution = getSolution();
        
        
        
    }

    
    
    public static function getSolution() {
        return "Orchies";
    }
    
    public static function getQuestion($idGare) {
        //TODO $idGare;
        return array( 1, //id de la question
            array (
                'question' => "Une commune française, située dans le département du Nord (59) en région Nord-Pas-de-Calais. Le nom jeté des habitants est les pourchots1, signifiant « porc » en picard.",
                'choix1' => "GareA",
                'choix2' => "Orchies",
                'choix3' => "GareC",
                'choix4' => "GareD",
            )
        );
    }

    public static function getSponsor($idQuestion) {
        return array( 1,
            array (
                'nom' => 'Starbuck',
                'FB' => 'http://lienfacebook/starbuck',
                'twitter' => null,
                'google' => null,
                'url' => 'http://www.starbuck.fr'
                )
            );
    }

    public static function getIdGare() {
        return 12434;
    }
}
