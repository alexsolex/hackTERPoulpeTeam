<?php


class Mobi_UserController extends Zend_Controller_Action
{   
        

    public function init()
    {
     $contextSwitch = $this->_helper->contextSwitch();
        $contextSwitch->addActionContext('connect', 'json')
                      ->initContext();
            
    }
    
    public function preDispatch() {

    }
    
        
    public function indexAction()
    {
      
    }

   
    public function loginAction()
    {
        
        $auth = TBS_Auth::getInstance();
        
        $providers = $auth->getIdentity();

        // Here the response of the providers are registered
        if ($this->_hasParam('provider')) {
            $provider = $this->_getParam('provider');
            
            $field = "";

            switch ($provider) {
                case "facebook":
                    if ($this->_hasParam('code')) {
                        $adapter = new TBS_Auth_Adapter_Facebook(
                                $this->_getParam('code'));
                        $result = $auth->authenticate($adapter);
                        $field = "FB";
                        $pseudo = $result->getIdentity($field)->getApi()->getProfile()["name"];
                    }
                    break;
                case "twitter":
                    if ($this->_hasParam('oauth_token')) {
                        $adapter = new TBS_Auth_Adapter_Twitter($_GET);
                        $result = $auth->authenticate($adapter);
                        $field = "tw";
                        $pseudoField = $result->getIdentity($field)->getApi()->getProfile()["screen_name"];
                    }
                    break;
                case "google":
                    if ($this->_hasParam('code')) {
                        $adapter = new TBS_Auth_Adapter_Google(
                                $this->_getParam('code'));
                        $result = $auth->authenticate($adapter);
                        $field = "google";
                        $pseudoField = $auth->getIdentity($field)->getApi()->getProfile()["nickname"];//A Vérifier
                    }
                    break;

            }
            // What to do when invalid
            if (!$result->isValid()) {
                $auth->clearIdentity($this->_getParam('provider'));
                throw new Exception('Error!!');
            } else {
                //c'est valide ! 
                //$this->manageConnexion($result);
                $this->_redirect('/mobi/user/connect');
            }
        } else { // Normal login page
            $this->view->googleAuthUrl = TBS_Auth_Adapter_Google::getAuthorizationUrl();

            $this->view->facebookAuthUrl = TBS_Auth_Adapter_Facebook::getAuthorizationUrl();

            $this->view->twitterAuthUrl = TBS_Auth_Adapter_Twitter::getAuthorizationUrl();
        }

    }
    
//    public static function manageConnexion($result) {
//        //Existe t'il un utilisateur avec ce provider ?
//        $tabParticipant = new Application_Model_DbTable_Participant();
//        $leParticipant = $tabParticipant->fetchRow(
//                $tabParticipant->select()
//                    ->where("tw = ?",$result->getIdentity("twitter")->getId())
//                    ->orWhere("google = ?",$result->getIdentity("google")->getId())
//                    ->orWhere("fb = ?",$result->getIdentity("facebook")->getId())
//                );
//        if (is_null($leParticipant)) {
//            //pas de participant avec ce réseau social, on le créé
//            $data = array(
//                $field=>$result->getIdentity($field)->getId(),
//                "pseudo"=>$pseudoField
//                    );
//            $leParticipant = $tabParticipant->createRow($data);
//            $leParticipant->save();
//        }
//    }
    
    public function connectAction()
    {
        $auth = TBS_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->view->message  = "non connecté";
            $this->_response->setHttpResponseCode(401);
            return;
        }
        $this->view->providers = $auth->getIdentity();
        
        // Recupération du participant qui pourrait correspondre au compte réseaux sociaux en session
        $tabParticipant = new Application_Model_DbTable_Participant();
        $select = $tabParticipant->select()->where("idParticipant=0");
        if ($auth->hasIdentity("twitter"))
            $select->orWhere ("tw = ?",$auth->getIdentity("twitter")->getId());
        if ($auth->hasIdentity("google"))
            $select->orWhere ("google = ?",$auth->getIdentity("google")->getId());
        if ($auth->hasIdentity("facebook"))
            $select->orWhere ("fb = ?",$auth->getIdentity("facebook")->getId());
        $leParticipant = $tabParticipant->fetchRow( $select );
        
        //préparation du tableau de données pour mettre à jour l'utilisateur
        $data = array();
        foreach ($auth->getIdentity() as $provider) {
            switch ($provider->getName()) {
                case 'facebook':
                    $data['fb'] = $provider->getId();
                    $data['pseudo'] = $provider->getApi()->getProfile()['name'];
                    break;
                case 'twitter':
                    $data['tw'] = $provider->getId();
                    $data['pseudo'] = $provider->getApi()->getProfile()['screen_name'];
                    break;
                case 'google':
                    $data['google'] = $provider->getId();
                    $data['pseudo'] = $provider->getApi()->getProfile()['nickname'];
                    break;
            }
        }
        //TODO  Attention, le pseudo est écrasé par chaque réseau social

        // Si le participant n'a pas été retrouvé via les réseaux sociaux, on considère 
        // qu'il s'agit d'un nouveau participant
        if (is_null($leParticipant)) {
            //  nouveau participant
            $leParticipant = $tabParticipant->createRow($data);
            $leParticipant->save();
            
        }
        else {
            //  mise à jour du participant trouvé
            $n = $tabParticipant->update($data,"idParticipant = ".$leParticipant->idParticipant);
            
        }
        $this->view->participant = $leParticipant->toArray();

        //La connection est terminée ( TODO restester )
        
        // maintenant on va servir les infos à la vue client question
        // 
        
        //la page quizz/index
        $this->_redirect('/mobi/quizz/index');
        
    }
    
     public function logoutAction()
    {
        TBS_Auth::getInstance()->clearIdentity();
        $this->_redirect('/');
    }

    public static function auth() {
        //on créé le connecteur d'authentification avec le connecteur de la BDD
        $authAdapter = new Zend_Auth_Adapter_DbTable ( Zend_Db_Table::getDefaultAdapter () );
        //on informe l'adapteur d'authentification de la table et des champs à utiliser pour l'identification
        $authAdapter->setTableName ( 'participant' ) //table des utilisateurs
                ->setIdentityColumn ( 'pseudo' ) //champ des identifiants
                //->setCredentialColumn ( 'pswUser' ) //champ des mdp
                //->setCredentialTreatment ( 'MD5('.$login.'?'.$password.')' ) //TODO tester la salaison
                //->setCredentialTreatment ( 'MD5(?)' ) //'MD5(?)' pour le hashage MD5
                ->setIdentity ( $pseudo ) //le login à vérifier
                ->setCredential ( $password ); //le psw à vérifier
        //lancement de la tentative d'authentification
        $authAuthenticate = $authAdapter->authenticate ();
        //vérification de l'authentification
        if (!$authAuthenticate->isValid ()) {
            //NOK : on affiche une erreur
            $this->getResponse()->setHttpResponseCode(401);
            $this->view->info = "CONNECTION_FAILED";
            return;
        }
        //ok : on met en session les infos de l'utilisateur
        // - récupération de l'espace de stockage de l'application
        $auth = Zend_Auth::getInstance ();
        $storage = $auth->getStorage ();
        // - écriture dans le stockage des infos de l'utilisateur (sans le mot de passe)
        $storage->write ( $authAdapter->getResultRowObject ( null, 'pswUser' ) );
        $idUser = $auth->getIdentity ()->idUser;
    }
}

