<?php

/**
 * gère l'évènement
 * TODO : documentation
 * */
class Application_Plugin_EvenementPlugin extends Zend_Controller_Plugin_Abstract {

    private $_evenement = null;
    private $_organisme = null;
     
    
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        parent::preDispatch($request);
        
        $module = $request->getModuleName();
        
        $front = Zend_Controller_Front::getInstance();
        $default = $front->getDefaultModule();
        
        switch ($module) {
            case $default:
                $this->handleDefault($request);
                break;
            case 'admin':
                $this->handleAdmin($request);
                break;
            case 'api':
                $this->handleApi($request);
                break;
            default:
                break;
        }
        

    }
    
    //
    // MODULE API
    //
    
    public function handleApi($request){
        return;
    }
            
    //
    // MODULE ADMIN
    //
    
    
    //gère le plugin pour le module admin
    public function handleAdmin($request){
        $this->getOrganisme();
        
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        
        //en cas d'erreur on ne redirige pas
        if ($controller=='error' && $action=='error') {
            return;
        }
        //TODO que faire avec la requête pour le module admin ? 
        //par exemple si pas d'organisme : rediriger sur la page de sélection de l'organisme ?

        if (is_null($this->_organisme)) { //nous ne sommes pas rattachés à un organisme
            if ($controller!='utilisateur') {
                if ( !($controller=='organisme' && $action=='lister') )// on laisse la liste des organismes
                {
                    //ce n'est pas organisme/lister qui est appelé, il faut rediriger dessus 
                    $front = Zend_Controller_Front::getInstance();
                    $request->setParam('infoDefautOrganisme',"Aucun organisme sélectionné il faut d'abord en sélectionner un !");
                    $request->setControllerName('organisme');
                    $request->setActionName('lister');
                    $front->returnResponse();
                }
            }
            
        }
        return;
    }
    
    public function getOrganisme(){
        $adminNameSpace = new Zend_Session_Namespace('admin');
        if (isset($adminNameSpace->organisme)) {
            $this->_organisme = $adminNameSpace->organisme;
            $this->_organisme->setTable(new Application_Model_DbTable_Organisme());
            //inscrit l'organisme dans le registre
            Zend_Registry::set('organismeAdmin',$this->_organisme );
        }
        else
        {
            $this->_organisme = null;
        }
    }
    
    //
    // MODULE DEFAULT
    //
    
    //gère le plugin pour le module default
    public function handleDefault($request){
        $this->getEvenement();
        
        $message = null;
        
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        //tous les cas qui redirigent si pas d'évènement
    }
    
    public function getEvenement(){
        $bulleNamespace = new Zend_Session_Namespace('bulle');
        //session active ?
        if (isset($bulleNamespace->checkedInEvent)) {
            $this->_evenement = $bulleNamespace->checkedInEvent;
            //La ligne qui suit est indispensable pour que les tables liées à la table évènement 
            //  soient mémorisées dans la session
            //  http://gustavostraube.wordpress.com/2010/05/11/zend-framework-cannot-save-a-row-unless-it-is-connected/
            $this->_evenement->setTable(new Application_Model_DbTable_Evenement());
        
            //inscrit l'évènement dans le registre
            Zend_Registry::set('checkedInEvent',$this->_evenement );
        }
        else
        {
            $this->_evenement = null;
        }
    }
    
    public function setRedirection($request,$message){
        $front = Zend_Controller_Front::getInstance();
        $defaultModule = $front->getDefaultModule();
        $request->setParam('infoDefautEvenement',$message);
        $request->setModuleName($defaultModule);
        $request->setControllerName('evenement');
        $request->setActionName('defaut');
        $front->returnResponse();       
    }
    
    public function checkout(){
        $defaultNamespace = new Zend_Session_Namespace('bulle');
        unset($defaultNamespace->checkedInEvent);
    }
    
    private function besoinRedirection($controlleur,$action){
        
        if ($controlleur == 'index' && $action == 'index') {
            return false;
        }
        
        if ($controlleur == 'evenement' && $action == 'accueil') {
            return true;
        }
        
        if ($controlleur!='evenement' && $controlleur!='utilisateur')  {
            return true;
        }
        
        //dans tous les cas non gérés, il n'y a pas besoin de redirection
        return false;
    }
}
?>
