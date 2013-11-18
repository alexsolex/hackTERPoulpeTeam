<?php

class ParticipantController extends Zend_Controller_Action
{
    private $_participant = null;

    public function init()
    {
       
    }

    /**
     * index : gère la situation lors de l'arrivée dans l'évènement
     * par défaut : affiche l'accueil de l'évènement
     *
     */
    public function indexAction()
    {
      $participants = new Application_Model_DbTable_Participant();
      $this->view->entries = $participants->fetchAll();
    }

}

