<?php

class Application_Model_Row_ParticiperRow extends Zend_Db_Table_Row_Abstract{
    protected $participant = null;
    protected $question = null;
    
    /**
     * @return l'utilisateur de la relation Apprecier
     */
    public function getParticipant()
    {
        try {
            if(!$this->participant){
            $this->participant = $this->findParentRow('Application_Model_DbTable_Participant');
            return $this->participant;}
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }   
    }
    
    /**
     * @return le message de la relation apprecier
     */
    public function getQuestion()
    {
        try {
            if(!$this->question){
            $this->question = $this->findParentRow('Application_Model_DbTable_Question');
            return $this->question;}
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }   
    }
   
}

