<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
//        $user = self::getUserFromAuth();
        if (!is_null($user)) {
            // $this->_helper->redirector ( 'profilprive', 'utilisateur' );
        }
    }
    
    public static function getUserFromAuth() {
        $auth = Zend_Auth::getInstance ();
        $utilisateur = null;
        if ($auth->hasIdentity ()) {
            $idUser = $auth->getIdentity ()->idUser;
            $tableUtilisateur = new Application_Model_DbTable_Utilisateur();
            $utilisateur = $tableUtilisateur->find($idUser)->current();

        }
        return $utilisateur;
        
    }


}

