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

 }
