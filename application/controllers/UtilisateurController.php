<?php

class UtilisateurController extends Zend_Controller_Action
{   
        
    private $_evenement;
    private $_session;
    
    public function init()
    {
        if (Zend_Registry::isRegistered('checkedInEvent')) {
            $this->_evenement = Zend_Registry::get('checkedInEvent');
        }
        $this->_session = new Zend_Session_Namespace('bulle');
        
            
    }
    
    public function preDispatch() {

    }
    
    
    public function indexAction()
    {
        //$Utilisateur = new Application_Model_DbTable_Utilisateur();
        //$this->view->entries = $Utilisateur->fetchAll();
        $utilisateur = $this->getUserFromAuth();

        $this->view->moderateur = $utilisateur->estModerateur($this->_evenement);
        $this->view->role = $utilisateur->getRole($this->_evenement->idOrga);
        $this->view->utilisateur = $utilisateur;
        $this->view->evenement = $this->_evenement;
        
    }

    
    public function loginAction()
    {
        
        $auth = TBS_Auth::getInstance();

        $providers = $auth->getIdentity();

        // Here the response of the providers are registered
        if ($this->_hasParam('provider')) {
            $provider = $this->_getParam('provider');

            switch ($provider) {
                case "facebook":
                    if ($this->_hasParam('code')) {
                        $adapter = new TBS_Auth_Adapter_Facebook(
                                $this->_getParam('code'));
                        $result = $auth->authenticate($adapter);
                    }
                    break;
                case "twitter":
                    if ($this->_hasParam('oauth_token')) {
                        $adapter = new TBS_Auth_Adapter_Twitter($_GET);
                        $result = $auth->authenticate($adapter);
                    }
                    break;
                case "google":
                    if ($this->_hasParam('code')) {
                        $adapter = new TBS_Auth_Adapter_Google(
                                $this->_getParam('code'));
                        $result = $auth->authenticate($adapter);
                    }
                    break;

            }
            // What to do when invalid
            if (!$result->isValid()) {
                $auth->clearIdentity($this->_getParam('provider'));
                throw new Exception('Error!!');
            } else {
                $this->_redirect('/utilisateur/connect');
            }
        } else { // Normal login page
            $this->view->googleAuthUrl = TBS_Auth_Adapter_Google::getAuthorizationUrl();

            $this->view->facebookAuthUrl = TBS_Auth_Adapter_Facebook::getAuthorizationUrl();

            $this->view->twitterAuthUrl = TBS_Auth_Adapter_Twitter::getAuthorizationUrl();
        }

    }
    
    public function connectAction()
    {
        $auth = TBS_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            throw new Zend_Controller_Action_Exception('Not logged in!', 404);
        }
        $this->view->providers = $auth->getIdentity();
    }
     public function logoutAction()
    {
        TBS_Auth::getInstance()->clearIdentity();
        $this->_redirect('/');
    }
    
    
    
    public static function getUserFromAuth(){
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

