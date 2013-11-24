<?php

class QuestionsController extends Zend_Controller_Action
{
   

    public function init() {
        //Context : on le force en json
        $this->_request->setParam('format', 'json');
        
        $contextSwitch = $this->_helper->contextSwitch();
        $contextSwitch->addActionContext('service', 'json')
                      
                      ->initContext();
    }

   
    public function indexAction()
    {
        
      
        
    }
    
    
    public function serviceAction(){
				//Créaion de l'appel au webservice avec soap..
				try{
//					$url = 'http://5.39.25.113:9000/sum-server/services/navitiaV3?wsdl';
//					$this->soapClient = new Zend_Soap_Client();
//                                        $this->soapClient->setWsdl($url);
//                                        $this->soapClient->setLocation('http://5.39.25.113:9000/sum-server/services/navitiaV3');
////                                        $this->soapClient->setAuth(base64_encode('opendata'), base64_encode('opendata'));
//					$this->soapClient->s(base64_encode('opendata'));
//                                        $this->soapClient->setPassword(base64_encode('opendata'));
//					$this->soapClient->setSoapVersion(SOAP_1_2);
                                $client = new SoapClient("http://5.39.25.113:9000/sum-server/services/navitiaV3?wsdl", 
                                       array("trace" => 1, "exception" => 0, "login" => 'opendata', "password" => 'opendata')); 

                                // Rꤵp鳥 les services..
                                $t = new Application_Model_DbTable_Gare();
                                $tableauGare = $t->fetchAll('uic > 1000');
                                
                                foreach ($tableauGare as $gare) {
                                    try {
                                        
                                    
                                    $parameters = array('in0' => array('codeUIC' => intval($gare->uic)));
                                    $result = $client->rechercherServicesGare($parameters); 
                                
   
                                  $simpleResult = new SimpleXMLElement($result->out->value);
                                     
                                    
                                    $soapServicesGare = $simpleResult->ServicesEnGare;
                         if(isset($soapServicesGare)){
                                    $presenceParking = isset($simpleResult->Parking) ? 
                                            1 :
                                            0;
                                    $presenceBus = isset($soapServicesGare->Bus) ? 
                                            1 :
                                            0;
                                    $presenceTramway = isset($soapServicesGare->Tramway) ? 
                                            1 :
                                            0;
                                    $presenceMetro = isset($soapServicesGare->Metro) ? 
                                            1 :
                                            0;
                                    $tabServicesOk = array(
                                        'Parking' => $presenceParking,
                                        'Bus' => $presenceBus,
                                        'Tramway' => $presenceTramway,
                                        'Metro' => $presenceMetro);
                                        
                                    $total = $presenceParking + $presenceBus + $presenceTramway + $presenceMetro;
                                    $intitule = 0;
                                   if ($total == 3) {
                                       print_r($tabServicesOk);
                                        $reponseJuste = null;
                                        foreach ($tabServicesOk as $key => $serviceOk) {
                                    
                                             if ($serviceOk == 0) {
                                                $reponseJuste = $key;
                                                break;
                                            }
                                        }
                                        
                                        $reponsesFausses = array();
                                        foreach ($tabServicesOk as $key => $serviceOk) {
                                            if ($serviceOk == 1) { print_r($key);
                                                array_push($reponsesFausses,$key);
                                           }
                                        }
                                         $intitule = 'Quel est le service qui n\'est pas encore propose en gare de ' . $gare->nomgare . '?';
                             
                                    }
                                    
//                                    var_dump($intitule);
                         }      
                            //INSERTION EN BASE
                            $ligne = new Application_Model_DbTable_Question();
                            $tab = array('libelle'=> utf8_encode($intitule), 
                                'reponse' => $reponseJuste,
                                'erreur1' => $reponsesFausses[0],
                                'erreur2' => $reponsesFausses[1],
                                'erreur3' => $reponsesFausses[2],
                                'url' => "", 
                                'type' => "service");
                            $newligne = $ligne->createRow($tab);
                            $newligne->save();
                            } catch (Exception $ex) {
                                        
                                    }                        
                                    
 }

                        } catch (SoapFault $ex) {
                                         echo '<div id="Erreur">'. $ex->getMessage().'</div>';
                        }catch (Exception $ex) {
                                echo '<div id="Erreur">Exception :'. $ex->getMessage().'</div>';
                        }
			
    }
}