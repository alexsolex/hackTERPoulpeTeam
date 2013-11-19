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
                      ->addActionContext('departs', 'json')
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
           $this->view->info = $result;
//           $this->view->info = $result->page;
        } catch (SoapFault $ex) {

                echo $ex->getMessage();
        }
  
    }
    
    public static function retard($tab){
        $etatOn; $retardOn;
        $etat = $tab->etat;
        $retard = $tab->retard;
        switch ($etat) {
            case 'ARR':
                $etatOn = 'Arrivé';
                break;
            case 'IND':
                $etatOn = 'Retard indéterminé';
                break;
            case 'SUP':
                $etatOn = 'Supprimé';
                break;
            default:
                $etatOn= '';
                break;
        }
        switch ($retard) {
            case '0005':
                $retardOn = 'Retard : 5 minutes';
                break;
            case '0010':
                $retardOn = 'Retard : 10 minutes';
                break;
            case '0015':
                $retardOn = 'Retard : 15 minutes';
                break;
            case '0020':
                $retardOn = 'Retard : 20 minutes';
                break;
            case '0025':
                $retardOn = 'Retard : 20 minutes';
                break;
            case '0030':
                $retardOn = 'Retard : 20 minutes';
                break;
            case '0040':
                $retardOn = 'Retard : 40 minutes';
                break;
            case '0050':
                $retardOn = 'Retard : 50 minutes';
                break;
            case '0100':
                $retardOn = 'Retard : 1 heure';
                break;
            case '0115':
                $retardOn = 'Retard : 1 heure 15 minutes';
                break;
            case '0130':
                $retardOn = 'Retard : 1 heure 30 minutes';
                break;
            case '0145':
                $retardOn = 'Retard : 1 heure 45 minutes';
                break;
            case '0200':
                $retardOn = 'Retard : 2 heures';
                break;
            case '0230':
                $retardOn = 'Retard : 2 heures 30 minutes';
                break;
            case '0300':
                $retardOn = 'Retard : 3 heures';
                break;
            case '0330':
                $retardOn = 'Retard : 3 heures 30 minutes';
                break;
            case '0400':
                $retardOn = 'Retard : 4 heures';
                break;
            default:
                $retardOn = '';
                break;
        }
        return $etat . $retard;
    }
    public static function logoEtType($picto, $type){
        $img = '';
        $typeBon = '';
        switch ($picto) {
            case '31':
                $img = "<img src=\"assets//img//picto//31.png\">";
                break;
            case '39':
                $img = "<img src=\"assets//img//picto//39.png\">";
                break;
            case '76':
                $img = "<img src=\"assets//img//picto//76.png\">";
                break;
            case '80':
                $img = "<img src=\"assets//img//picto//80.png\">";
                break;
            case '91':
                $img = "<img src=\"assets//img//picto//91.png\">";
                break;
            case '92':
                $img = "<img src=\"assets//img//picto//92.png\">";
                break;
            case '96':
                $img = "<img src=\"assets//img//picto//96.png\">";
                break;
            case '97':
                $img = "";
                break;
            case '98':
                $img = "";
                break;
            default:
                $img = "<img src=\"assets//img//picto//30.png\">";
                break;
        }
        
        switch ($type) {
            case 'ICE':
                $typeBon = 'TGV/ICE';
                break;
            case 'LYRIA':
                $typeBon = 'TGV/LYRIA';
                break;
            case 'TGV PREMS':
                $typeBon = 'TGV';
                break;
            case 'TER AQUITAINE':
                $typeBon = 'TER';
                break;
            case 'TER CENTRE':
                $typeBon = 'TER';
                break;
            case 'TER MIDI PYRENNEES':
                $typeBon = 'TER';
                break;
            case 'TER NORD PAS DE CALAIS':
                $typeBon = 'TER';
                break;
            case 'TER PICARDIE':
                $typeBon = 'TER';
                break;
            case 'CORAIL':
                $typeBon = 'Intercités';
                break;
            case 'TEOZ':
                $typeBon = 'Intercités';
                break;
            case 'LUNEA':
                $typeBon = 'Intercités';
                break;
            case 'TEOZ ECO':
                $typeBon = 'Intercités';
                break;
            case 'INTERCITES DE NUIT':
                $typeBon = 'Intercités';
                break;
            case 'RUSSIAN RAILWAYS':
                $typeBon = 'RZD';
                break;
            case 'CONSEIL GENERAL 13':
                $typeBon = 'CG13';
                break;
            case 'LIGNES EXPRESS REGIONALES':
                $typeBon = 'LER';
                break;
            case 'TER VALLEE DE LA MARNE':
                $typeBon = 'TER';
                break;
            case 'TRAM TRAIN':
                $typeBon = '';
                break;
            case 'CONSEIL GENERAL 66':
                $typeBon = '';
                break;
            default:
                $typeBon = $type;
                break;
        }
        $tabLogoType = array('logo'=>$img, 'type'=>$typeBon);
        return $tabLogoType;
    }
    public function departsAction() {
        $soapResults = $this->departs('LEW');
        $retour = array();
        foreach ($soapResults->train as $train) {
            $tabLogoType = $this->logoEtType($train->picto, $train->type);
            $retour[] = array(
                $tabLogoType['logo'], //"logo",
                $tabLogoType['type'],//      "transporteur",
                $train->num, //      "numéro de train",
                substr($train->heure,11,5),//      "heure depart",
                $train->origdest,//      "destination",
                $this->retard($train->trainTypeChoice),//      "information",
                $train->voie//      "voie"
                );
        }
                    
//                "attribut_voie": "",
//                "heure": "2013-11-19T17:35:00.000Z",
//                "ligne": 1,
//                "num": "9153",
//                "origdest": "ST PANCRAS",
//                "picto": "31",
//                "trainTypeChoice": {
//                    "etat": "",
//                    "retard": "",
//                    "valid": false
//                },
//                "type": "EUROSTAR",
//                "valid": false,
//                "voie": "4"
        
        $this->view->aaData = $retour;
    }
    
    
    public function departs($codeGare){
        try{
            
           $result = $this->soapClient->getTableauTrainsDepart($codeGare);
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
        return $result;
           
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



