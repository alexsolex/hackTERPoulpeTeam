<?php

/**
 * Classe de création des ACL via un fichier de configuration INI
 * */
//class Application_Plugin_PluginAuth extends Zend_Controller_Plugin_Abstract {
class Application_Plugin_AuthPlugin extends Zend_Controller_Plugin_Abstract {

    /**
     * @var Zend_Auth instance 
     */
    private $_auth;

    /**
     * @var Zend_Acl instance 
     */
    private $_acl;

    
    
    /*
     * stocker la session
     */
    private $_session;

    /**
     * Chemin de redirection lors de l'échec d'authentification
     */

    const FAIL_AUTH_MODULE = 'default';
    const FAIL_AUTH_ACTION = 'authentifier';
    const FAIL_AUTH_CONTROLLER = 'utilisateur';
    const FAIL_AUTH_PARAMS = null;

    /**
     * Chemin de redirection lors de l'échec de contrôle des privilèges
     */
    const FAIL_ACL_MODULE = 'default';
    const FAIL_ACL_ACTION = 'index';
    const FAIL_ACL_CONTROLLER = 'index';
    const FAIL_ACL_PARAMS = null;

    /**
     * Constructeur
     */
    public function __construct(Zend_Acl $acl) {
        $this->_acl = $acl;
        $this->_auth = Zend_Auth::getInstance();
        $this->_session = new Zend_Session_Namespace('bulle');
    }

    /**
     * Vérifie les autorisations
     * Utilise _request et _response hérités et injectés par le FC
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        
        //chargement de l'évènement depuis le registre (évènement géré en amont par le plugin EvenementPlugin.php)
        if (Zend_Registry::isRegistered('checkedInEvent')) {
            $this->_evenement = Zend_Registry::get('checkedInEvent');
        }
 
        //récupération des module/controller/action/params pour la ressource actuellement demandée
        $reqModule      = $request->getModuleName();
        $reqController  = $request->getControllerName();
        $reqAction      = $request->getActionName();
        $reqParams      = $request->getParams();
        
       
        $front = Zend_Controller_Front::getInstance();
        $default = $front->getDefaultModule();

        // compose le nom de la ressource
        if ($reqModule == $default) {
            $resource = $reqController;
        } else {
            $resource = $reqModule . '_' . $reqController;
        }

        
        // est-ce que la ressource demandée est soumise aux ACLs ?
        if (!$this->_acl->has($resource)) { //la ressource demandée n'est pas prévue dans les ACLs
            $resource = null;
            $reqAction = null;
            //return;
        }
        

        //Si l'utilisateur n'est pas authorisé à accéder à la ressource
        if ($this->_acl->has($resource) && !$this->_acl->isAllowed($role, $resource, $reqAction)) {
            
            //si l'utilisateur n'a pas de session
            if (!$this->_auth->hasIdentity()) {
                
                //on récupère en session les infos de redirection
               if (!isset($this->_session->redirection))  {
                    $this->_session->redirection = $reqParams;
                }
                
                // paramètres de route en cas d'utilisateur non connecté n'ayant pas le droit d'accès à la ressource
                $out = $this->getFailAuthParams($reqModule);
                $outModule      = $out['module'];
                $outController  = $out['controller'];
                $outAction      = $out['action'];
                $outParams      = $out['params'];
                
            } else {
                // l'utilisateur actuel est connecté 
                $out = $this->getFailACLParams($reqModule);
                $outModule      = $out['module'];
                $outController  = $out['controller'];
                $outAction      = $out['action'];
                $outParams      = $out['params'];

            }
            
        } 
        else //l'utilisateur a le droit d'accéder à la ressource
            { 
            
            
            //si l'utilisateur n'est pas connecté
            if (!$this->_auth->hasIdentity()) {
                
            } else {
                //l'utilisateur actuel est connecté
                
            }
        }
        
        
        //si le plugin devait effectuer la redirection
        if ($redirecting) {
             unset($this->_session->redirection);
             //$request->setParam('needRedirect', false);
             $outParams["needRedirect"]=false;
             //unset($outParams["needRedirect"]);
             $request->setPost($outParams);
             $_SERVER['REQUEST_METHOD']='POST';
             
        }
        
        //configure la requête pour la suite à donner
        //$request->setModuleName($outModule);
        //ligne ci dessus modifiée pour fonctionner avec les modules. 
        // Les redirections doivent être faites sur le module qui a demandé la ressource
        $request->setModuleName($reqModule); 
        $request->setControllerName($outController);
        $request->setActionName($outAction);
        
        if (!is_null($outParams)) //gère le cas des params à null
        { 
            $request->clearParams();
            $request->setParams($outParams);
            
        }

        
        
    }

    private function getUserRole() {
        $role = 'visiteur'; //par défaut
        //si l'utilisateur en cours est connecté
        if ($this->_auth->hasIdentity()) {
            // nous avons à faire à un utilisateur connecté ! 
            // il sera donc AU MOINS utilisateur
            $role = 'utilisateur';
            //peut être a t'il un rôle plus important ?
            //on récupère l'utilisateur
            $idUser = $this->_auth->getIdentity()->idUser;
            $tableUtilisateur = new Application_Model_DbTable_Utilisateur();
            $user = $tableUtilisateur->find($idUser)->current();

            //puis on va récupérer son rôle l'organisme
            if (Zend_Registry::isRegistered('organismeAdmin')) {
                $orga = Zend_Registry::get('organismeAdmin');
                $role = $user->getRole( $orga->idOrga );
            } else {
                if (!is_null($this->_evenement)) {
                    //$role = $user->getRole($this->_evenement->idOrga);
                    $role = $user->getRole($this->_evenement->idOrga);
                }
            }
            

        } else {

            }
        return $role;
    }

    private function getFailAuthParams($module){
        switch ($module) {
            case 'default':
                return array(
                    'module'=>self::FAIL_AUTH_MODULE , 
                    'controller'=>self::FAIL_AUTH_CONTROLLER , 
                    'action'=>self::FAIL_AUTH_ACTION ,
                    'params'=>self::FAIL_AUTH_PARAMS
                    );
                break;
            case 'admin':
                return array(
                    'module'=>'admin' ,
                    'controller'=>'index' ,
                    'action'=>'index',
                    'params'=>null);
                break;
            default:
                return array(
                    'module'=>'default' ,
                    'controller'=>'index' ,
                    'action'=>'index',
                    'params'=>null);
                break;
        }
    }
        
    private function getFailACLParams($module){
        switch ($module) {
            case 'default':
                return array(
                    'module'=>self::FAIL_ACL_MODULE , 
                    'controller'=>self::FAIL_ACL_CONTROLLER , 
                    'action'=>self::FAIL_ACL_ACTION ,
                    'params'=>self::FAIL_ACL_PARAMS
                    );
                break;
            case 'admin':
                return array(
                    'module'=>'admin' ,
                    'controller'=>'index' ,
                    'action'=>'index',
                    'params'=>null);
                break;
            default:
                return array(
                    'module'=>'default' ,
                    'controller'=>'index' ,
                    'action'=>'index',
                    'params'=>null);
                break;
        }
         
        
    }
}