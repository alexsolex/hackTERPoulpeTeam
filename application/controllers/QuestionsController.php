<?php

class QuestionsController extends Zend_Controller_Action
{
   

    public function init() {
        //Context : on le force en json
        $this->_request->setParam('format', 'json');
        
        $contextSwitch = $this->_helper->contextSwitch();
        $contextSwitch->addActionContext('index', 'json')
                      ->addActionContext('wikiextract', 'json')
                      ->initContext();
    }

   
    public function indexAction()
    {
        
        
    }
    
    public function wikiextractAction()
    {
        //$test = json_decode('{"query":{"pages":{"192855":{"pageid":192855,"ns":0,"title":"Orchies","extract":"Orchies est une commune fran\u00e7aise, situ\u00e9e dans le d\u00e9partement du Nord (59) en r\u00e9gion Nord-Pas-de-Calais.\nLe nom jet\u00e9 des habitants est les pourchots, signifiant \u00ab porc \u00bb en picard.\n\n"}}}}');
        
        $tQuestion = new Application_Model_DbTable_Question();
        $tGare = new Application_Model_DbTable_Gare();
        
        $tableauGare = $tGare->fetchAll($tGare->select()->where('region = "Nord-Pas-de-Calais"'));
        $i = 0;
        $gare = null;
        //$this->view->url = $fql_query_url;
        foreach ($tableauGare as $g) {
            $gare = $g;
            $fql_query_url = "http://fr.wikipedia.org/w/api.php?action=query&prop=extracts&format=json&exlimit=2&exintro=&explaintext=&exsectionformat=plain&titles=".$g->nomgare."&redirects=";
            $rep = file_get_contents($fql_query_url);
            
            $repJson = json_decode($rep);
            //$this->view->url = $fql_query_url;
            //$this->view->repJson = $repJson;
            $page = null;
            foreach ($repJson->query->pages as $p) {
                $page = $p;
                //$this->view->page = $page;
            }
            //$libelle = $repJson->query->pages;
            //$this->view->lib = $libelle;
            $this->view->title = $page->title;
            
            if (count_chars($page->extract)<1)
                continue;
            
            $libelle = substr($page->extract, 0, 999);

            $libelle = str_replace($page->title, "* *", $libelle);
            
            $libelle = trim($libelle, " \n");
            $libelle = preg_replace("/\[.*\]/","",$libelle);
            //$libelle = str_replace("\n", "<br/>", $libelle);
            $libelle = preg_replace("/\(.*\)/","",$libelle);
            $mauvaisesReponses = $tGare->get3ByNomGare($g->nomgare)->toArray();
            //$this->view->test = array_rand($mauvaisesReponses->toArray());
            $data = array(
                "libelle" => $libelle ,
                "reponse" =>$gare->nomgare,
                "type" => "wikiextract",
                "erreur1" => $mauvaisesReponses[0]["nomgare"],
                "erreur2" => $mauvaisesReponses[1]["nomgare"],
                "erreur3" => $mauvaisesReponses[2]["nomgare"]
            );
           $question = $tQuestion->createRow($data);
           $question->save();
            $i++;
        }
        $this->view->compteur = $i;
    }
    
}