

<?php
 
require_once('Zend/Soap/Client.php');
class EcrangareController extends Zend_Controller_Action
{
    private $_ecran = null;
    private $soapClient;
    public function init()
    {        
        $array = array('login'=> base64_encode('opendata'),'password'=> base64_encode('opendata'));
        $endpoint = 'http://5.39.25.113:9000/sum-server/services/navitiaV3?WSDL';
        try{
            $this->soapClient = new Zend_Soap_Client();
            $this->soapClient->setWsdl($endpoint);
            $this->soapClient->setOptions($array);
            
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
        try{
           $result = $this->soapClient->getWsdl();
            $this->view->reponse = $result;    
        } catch (SoapFault $ex) {

                echo $ex->getMessage();
        }
  
    }
}



