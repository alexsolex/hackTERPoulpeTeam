<?php

class Mobi_IndexController extends Zend_Controller_Action {

    const NO_AUTH_POSSIBLE = false;
    
    public function init() {
        
        
    }

    public function indexAction() {
        //simple redirection pour l'url de base
        if (self::NO_AUTH_POSSIBLE) {
            
        }
        else {
            $this->_redirect('/mobi/user/index');
        }
        
        
    }

    
//    public static function getQuestion($idGare) {
//        //TODO $idGare;
//        return array( 1, //id de la question
//            array (
//                'question' => "Une commune française, située dans le département du Nord (59) en région Nord-Pas-de-Calais. Le nom jeté des habitants est les pourchots1, signifiant « porc » en picard.",
//                'choix1' => "GareA",
//                'choix2' => "Orchies",
//                'choix3' => "GareC",
//                'choix4' => "GareD",
//            )
//        );
//    }
//
//    public static function getSponsor($idQuestion) {
//        return array( 1,
//            array (
//                'nom' => 'Starbuck',
//                'FB' => 'http://lienfacebook/starbuck',
//                'twitter' => null,
//                'google' => null,
//                'url' => 'http://www.starbuck.fr'
//                )
//            );
//    }
//
//    public static function getIdGare() {
//        return 12434;
//    }
}
