<?php

class TestController extends Zend_Controller_Action
{
   

    public function init() {
        //Context : on le force en json
        $this->_request->setParam('format', 'json');
        
        $contextSwitch = $this->_helper->contextSwitch();
        $contextSwitch->addActionContext('index', 'json')
                      
                      ->initContext();
    }

   
    public function indexAction()
    {
        
//        $t = new Application_Model_DbTable_Gain();
//        $this->view->gain = $t->fetchAll()->toArray();
//        
//        $t = new Application_Model_DbTable_Gare();
//        $this->view->gare = $t->fetchAll()->toArray();
//        
//        $t = new Application_Model_DbTable_Partenaire();
//        $this->view->partenaire = $t->fetchAll()->toArray();
//        
//        $t = new Application_Model_DbTable_Participant();
//        $this->view->participant = $t->fetchAll()->toArray();
//        
//        $t = new Application_Model_DbTable_Participer();
//        $this->view->participer = $t->fetchAll()->toArray();
//        
//        $t = new Application_Model_DbTable_Question();
//        $this->view->question = $t->fetchAll()->toArray();
//        
//        $t = new Application_Model_DbTable_Quizz();
//        $this->view->quizz = $t->fetchAll()->toArray();
//        
//        $t = new Application_Model_DbTable_Situer();
//        $this->view->situer = $t->fetchAll()->toArray();
        
        // exemple wikipedia
//        
        
        $t = new Application_Model_DbTable_Gare();
        $tableauGare = $t->fetchAll();
       $i = 0;
        foreach ($tableauGare as $resRow ){
//            sleep()
////            modif des coordonnees
            if($resRow->ville == ""){
                $fql_query_url = 'http://fr.wikipedia.org/w/api.php?format=json&action=query&titles=' . $resRow->ville . '&prop=revisions&rvprop=content';
                $fql_query_result = file_get_contents($fql_query_url);
                $fql_query_obj = json_decode($fql_query_result, true);
            
            }
            
        }
        $this->view->json = $fql_query_obj;
//    
//        $ville = 'Lille';
//	
//	$queryUrl = 'http://api.openweathermap.org/data/2.5/weather?q=' . $ville . '&units=metric&lang=fr';
//	
//	$result = file_get_contents($queryUrl);
//	$tabResult = json_decode($result, true);
//        
//        $this->view->result = $tabResult;

        
	//Recherche des villes, régions...en fonction des coordonnées
//	$queryUrl = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=50.650,3.083&sensor=true';
	
//	$result = file_get_contents($queryUrl);
//	$tabResult = json_decode($result, true);
        
        
        //INSERTION DES DONNEES VILLES,REGIONS,CODEPOST
//        $geo = new calculGeoloc();
//        $t = new Application_Model_DbTable_Gare();
//        $tableauGare = $t->fetchAll("codepost is null");
//       $i = 0;
//        foreach ($tableauGare as $resRow ){
////            sleep()
//////            modif des coordonnees
//            if($resRow->ville == ""){
//            $xGare = $resRow['Xcoord'];
//            $yGare = $resRow['Ycoord'];
////            $xGare = 532222;
////            $yGare = 2029089;
//            if($xGare != null && $yGare != null){
//            $coordsGare = $geo->getLambert93VersWGS84($xGare, $yGare);           
////            var_dump($coordsGare);
//            $queryUrl = 'http://nominatim.openstreetmap.org/reverse?format=json&lat=' . $coordsGare["latitude"] . '&lon=' . $coordsGare["longitude"] .'&zoom=18&addressdetails=1&sensor=true';
//            $result = file_get_contents($queryUrl);
//            $tabResult = json_decode($result, true);
////            print_r($tabResult) ;
//            $tab = $tabResult['address'];
//            //Ajout des données dans la ligne de la table
////            print_r($res) ;
//            
//             $resRow->ville = (isset($tab['city']))? $tab['city']: "";;
//             $resRow->region = (isset($tab['state']))? $tab['state']: "";;
//             $resRow->codepost = (isset($tab['postcode']))? $tab['postcode']: "";
//             $resRow->save(); 
//            }
//            }
//            
//        }
      
        
    }
    
    public static function rechercherAdresse($tf, $typeRecherche)
    {
        if(isset($tf['address_components'])){
            foreach ($tf['address_components'] as $address_component) {
                if(isset($address_component["types"])){
//                    var_dump($address_component);
                  $types = $address_component["types"];
                   
                
                    foreach ($types as $type) {
                            if ($type == $typeRecherche) {
                                return $types["long_name"];
                            }
                        }
                    }
                
            }
            
    }
    return null;
    }
 
   
//public function listerFacebook() {
//
//        //requete FQL
//        
//
//    }
}

class calculGeoloc {
    /*
     * lambert 93 vers nos coords 
     */
    public function getLambert93VersWGS84($x, $y)
    {
        return $this->Lambert2WGS84("LIIe", $x, $y);
    }

    
    public  function getDistance($latitude1, $longitude1, $latitude2, $longitude2) {  
        $earth_radius = 6371;  

        $dLat = deg2rad($latitude2 - $latitude1);  
        $dLon = deg2rad($longitude2 - $longitude1);  

        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);  
        $c = 2 * asin(sqrt($a));  
        $d = $earth_radius * $c;  

        return $d;  
    }  

    private function ALG0001($phi,$e)
    {
            $temp = ( 1 - ( $e * sin( $phi ) ) ) / ( 1 + ( $e * sin( $phi ) ) );

            $L = log ( tan ( (pi()/4) + ($phi/2) ) * pow ($temp, ($e/2) ));

            return $L;
    }

    private function ALG0002($L,$e,$epsilon)
    {
            $phi[0] = 2 * atan(exp($L)) - (pi()/2);

            $i=0;
            do
            {
                    $i++;
                    $temp = ( 1 + ( $e * sin( $phi[$i-1] ) ) ) / ( 1 - ( $e * sin( $phi[$i-1] ) ) );
                    $phi[$i] = 2 * atan ( pow ($temp, ($e/2)) * exp ($L) ) - pi()/2;
            }
            while (abs($phi[$i] - $phi[$i - 1]) >= $epsilon);

            return $phi[$i];
    }

    private function ALG0004($X,$Y,$n,$c,$Xs,$Ys,$lambdac,$e,$epsilon)
    {
            $R = sqrt( pow(($X - $Xs),2) + pow(($Y - $Ys),2) );
            $gamma = atan(($X - $Xs)/($Ys - $Y));

            $lambda = $lambdac + ($gamma / $n);

            $L = (-1 / $n) * log(abs($R/$c));

            $phi = $this->ALG0002($L,$e,$epsilon);

            $coords['lambda']=$lambda;
            $coords['phi']=$phi;

            return $coords;
    }

    private  function ALG0009($lambda,$phi,$he,$a,$e)
    {
            $N = $this->ALG0021($phi,$a,$e);

            $X = ($N + $he) * cos($phi) * cos($lambda);

            $Y = ($N + $he) * cos($phi) * sin($lambda);

            $Z = ($N * (1 - $e*$e)  + $he) * sin ($phi);

            $coords['X']=$X;
            $coords['Y']=$Y;
            $coords['Z']=$Z;

            return $coords;
    }


    private function ALG0012($X,$Y,$Z,$a,$e,$epsilon)
    {
            $lambda = atan ($Y/$X);

            $P = sqrt($X*$X + $Y*$Y);
            $phi[0] = atan ($Z/ ($P * (1 - ( ($a*$e*$e)/sqrt($X*$X + $Y*$Y + $Z*$Z) ) ) ) );

            $i = 0;
            do
            {
                    $i++;
                    $temp =  pow((1 - ( $a * $e*$e * cos($phi[$i - 1] )/( $P * sqrt(1 - $e*$e * sin($phi[$i - 1])*sin($phi[$i - 1]))))),-1);
                    $phi[$i] = atan( $temp * $Z / $P );
            }
            while (abs($phi[$i] - $phi[$i - 1]) >= $epsilon);

            $phix = $phi[$i];

            $he = ($P/cos($phix)) - ($a/sqrt(1 - $e*$e * sin($phix)*sin($phix)));

            $coords['lambda']=$lambda;
            $coords['phi']=$phix;
            $coords['he']=$he;

            return $coords;
    }

    private function ALG0013($Tx,$Ty,$Tz,$D,$Rx,$Ry,$Rz,$U)
    {
            $V['X'] = $Tx + $U['X'] * (1 + $D) + $U['Z'] * $Ry - $U['Y'] * $Rz;
            $V['Y'] = $Ty + $U['Y'] * (1 + $D) + $U['X'] * $Rz - $U['Z'] * $Rx;
            $V['Z'] = $Tz + $U['Z'] * (1 + $D) + $U['Y'] * $Rx - $U['X'] * $Ry;

            return $V;
    }

    private function ALG0019($lambda0,$phi0,$k0,$X0,$Y0,$a,$e)
    {
            $lambdac = $lambda0;
            $n = sin($phi0);
            $C = $k0 * $this->ALG0021($phi0,$a,$e) * tan (pi()/2 - $phi0) * exp ( $n * $this->ALG0001($phi0,$e) );
            $Xs = $X0;
            $Ys = $Y0 + $k0 * $this->ALG0021($phi0,$a,$e) * tan (pi()/2 - $phi0) ;

            $tab ['e'] = $e;
            $tab ['n'] = $n;
            $tab ['C'] = $C;
            $tab ['lambdac'] = $lambdac;
            $tab ['Xs'] = $Xs;
            $tab ['Ys'] = $Ys;

            return $tab;

    }

    private function ALG0021($phi,$a,$e)
    {
            $N = $a/sqrt( 1 - $e * $e * sin($phi) * sin($phi) );
            return $N;

    }

    private function ALG0054($lambda0,$phi0,$X0,$Y0,$phi1,$phi2,$a,$e)
    {
            $lambdac = $lambda0;
            $n = ( (log( ($this->ALG0021($phi2,$a,$e)*cos($phi2))/($this->ALG0021($phi1,$a,$e)*cos($phi1)) ))/($this->ALG0001($phi1,$e) - $this->ALG0001($phi2,$e) ));
            $C = (($this->ALG0021($phi1,$a,$e)* cos($phi1))/$n) * exp($n * $this->ALG0001($phi1,$e));


            if ($phi0 == (pi()/2))
            {
                    $Xs = $X0;
                    $Ys = $Y0;
            }
            else
            {
                            echo ('coucou');
                    $Xs = $X0;
                    $Ys = $Y0 + $C * exp(-1 * $n * $this->ALG0001($phi0,$e));
            }

            $tab ['e'] = $e;
            $tab ['n'] = $n;
            $tab ['C'] = $C;
            $tab ['lambdac'] = $lambdac;
            $tab ['Xs'] = $Xs;
            $tab ['Ys'] = $Ys;

            return $tab;

    }

    public function Lambert2WGS84($orig,$X,$Y)
    {
            $epsilon = 0.00000000001;

            switch ($orig)
            {
                    case 'LII' :
                            $n = 0.7289686274;
                            $c = 11745793.39;
                            $Xs = 600000;
                            $Ys = 6199695.768;
                            $lambdac = 0.04079234433; // pour greenwich

                            $e = 0.08248325676; //(première excentricité de l’ellipsoïde Clarke 1880 français)

                            $he = 100;
                            $a = 6378249.2;

                            $Tx = -168;
                            $Ty = -60;
                            $Tz = +320;
                            $D  = 0;
                            $Rx = $Ry = $Rz = 0;
                    break;
                    case 'LIIe' :

                            $n = 0.7289686274;
                            $c = 11745793.39;
                            $Xs = 600000;
                            $Ys = 8199695.768;
                            $lambdac = 0.04079234433; // pour greenwich

                            $e = 0.08248325676; //(première excentricité de l’ellipsoïde Clarke 1880 français)

                            $he = 100;
                            $a = 6378249.2;

                            $Tx = -168;
                            $Ty = -60;
                            $Tz = +320;
                            $D  = 0;
                            $Rx = $Ry = $Rz = 0;
                    break;
                    case 'L93' :
                            $n = 0.7256077650;
                            $c = 11745255.426;
                            $Xs = 700000;
                            $Ys = 12655612.050;
                            $lambdac = 0.04079234433; // pour greenwich

                            $e = 0.08248325676; //(première excentricité de l’ellipsoïde Clarke 1880 français)

                            $he = 100;
                            $a = 6378249.2;

                            $Tx = -168;
                            $Ty = -60;
                            $Tz = +320;
                            $D  = 0;
                            $Rx = $Ry = $Rz = 0;
                    break;
            }

            $coords = $this->ALG0004($X,$Y,$n,$c,$Xs,$Ys,$lambdac,$e,$epsilon);

            $coords = $this->ALG0009($coords['lambda'],$coords['phi'],$he,$a,$e);

            $coords = $this->ALG0013($Tx,$Ty,$Tz,$D,$Rx,$Ry,$Rz,$coords);

            $a = 6378137.0;    // ellipsoïdes WGS84
            $f = 1/298.257223563;
            $b = $a*(1-$f);
            $e = sqrt(($a*$a - $b*$b)/($a*$a));

            $X = $coords['X'];
            $Y = $coords['Y'];
            $Z = $coords['Z'];
            $coords = $this->ALG0012($X,$Y,$Z,$a,$e,$epsilon);

            $coords['longitude']=rad2deg($coords['lambda']);
            $coords['latitude']   =rad2deg($coords['phi']);
            return $coords;
    }
}