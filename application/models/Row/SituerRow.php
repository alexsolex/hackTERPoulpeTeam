<?php

class Application_Model_Row_SituerRow extends Zend_Db_Table_Row_Abstract{
    protected $partenaire = null;
    protected $gare = null;
    
    /**
     * @return l'utilisateur de la relation Apprecier
     */
    public function getPartenaire()
    {
        try {
            if(!$this->partenaire){
            $this->partenaire = $this->findParentRow('Application_Model_DbTable_Partenaire');
            return $this->partenaire;}
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }   
    }
    
    /**
     * @return le message de la relation apprecier
     */
    public function getGare()
    {
        try {
            if(!$this->gare){
            $this->gare = $this->findParentRow('Application_Model_DbTable_Gare');
            return $this->gare;}
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }   
    }
   
}