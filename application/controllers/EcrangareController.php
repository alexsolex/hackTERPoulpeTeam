

<?php
 

class EcrangareController extends Zend_Controller_Action
{
    private $soapClient;
    
    public function init()
    {        
        //Création de l'appel au webservice avec soap
        try{
            $endpoint = 'http://gares-en-mouvement.com/tvs/TVS?wsdl';
            $this->soapClient = new Zend_Soap_Client();
            $this->soapClient->setWsdl($endpoint);
            $this->soapClient->setSoapVersion(SOAP_1_1);
            
        } catch (Exception $ex) {
                echo $ex->getMessage();
        }
       
        //Context : on le force en json
        //$this->_request->setParam('format', 'json');
        
        $contextSwitch = $this->_helper->contextSwitch();
        $contextSwitch->addActionContext('index', 'json')
                      
                      ->initContext();
    }

    /**
     * index : gère la situation lors de l'arrivée dans l'évènement
     * par défaut : affiche l'accueil de l'évènement
     *
     */
    public function indexAction()
    {   
        //Codé en dur
        //Il faut voir si on veut avoir du dynamisme ici
        //Si on a le temps sinon ça me semble pas super important
        //Un message et c'est tout
        try{
           $result = $this->soapClient->getTableauInfos('LEW','1','20131119T00:00:00.000'); 
            $this->view->info = $result->page;
        } catch (SoapFault $ex) {

                echo $ex->getMessage();
        }
  
    }
    
    public function departs(){
        try{
           $result = $this->soapClient->getTableauTrainsDepart('LEW');
           //$result est le retour de l'appel au webservice
           //Dois retourner
           //Pour les champs non renseignés => ""
                        //{
                        //  "aaData": [
                        //    [
                        //      "logo",
                        //      "transporteur",
                        //      "numéro de train",
                        //      "heure depart",
                        //      "destination",
                        //      "information",
                        //      "voie"
                        //    ],
                        //    [
                        //       "logo",
                        //      "transporteur",
                        //      "numéro de train",
                        //      "heure depart",
                        //      "destination",
                        //      "information",
                        //      "voie"
                        //    ],
                        //    [
                        //       "logo",
                        //      "transporteur",
                        //      "numéro de train",
                        //      "heure depart",
                        //      "destination",
                        //      "information",
                        //      "voie"
                        //    ]
                        //      
                        //}
        } catch (SoapFault $ex) {
                echo $ex->getMessage();
        }
    }
    
    public function arrivees(){
        try{
           $result = $this->soapClient->getTableauTrainsArrivee('LEW');
           //$result est le retour de l'appel au webservice
           //Idem que pour les departs
        } catch (SoapFault $ex) {
                echo $ex->getMessage();
        }
    }
}



