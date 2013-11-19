<?php

class Mobi_IndexController extends Zend_Controller_Action {

    public function init() {
        
        
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
