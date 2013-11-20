<?php

class Application_Model_DbTable_Question extends Zend_Db_Table_Abstract
{
    protected $_name = 'question';
    protected $_rowClass = 'Application_Model_Row_QuestionRow';
    protected $_rowsetClass = 'Application_Model_Rowset_QuestionRowset';
    protected $_dependentTables = array('Application_Model_DbTable_Participer');
    protected $_referenceMap    = array(
        'gagner' => array(
            'columns'           => 'idParticipant',
            'refTableClass'     => 'Application_Model_DbTable_Participant',
            'refColumns'        => 'idParticipant'
        ),
         'offrir' => array(
            'columns'           => 'idGain',
            'refTableClass'     => 'Application_Model_DbTable_Gain',
            'refColumns'        => 'idGain'
        ),
         'appartenir' => array(
            'columns'           => 'idGare',
            'refTableClass'     => 'Application_Model_DbTable_Gare',
            'refColumns'        => 'idGare'
        ),'poser' => array(
            'columns'           => 'idPartenaire',
            'refTableClass'     => 'Application_Model_DbTable_Partenaire',
            'refColumns'        => 'idPartenaire'
        ));

     
    public function getQuestion($idGare) {
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array('q'=>'question'),
                    array('idQuestion','libelle','dateDebut','dateFin','reponse','estRepondu','badReponse1','badReponse2','badReponse3'))
            ->joinLeft(array('a'=>'apprecier'),
                    'm.idMessage = a.idMessage',
                    array('sum(if(a.evaluation>0,1,0)) as like','sum(if(a.evaluation<0,1,0)) as dislike'))
            ->joinInner(array('u'=>'utilisateur'),
                    'm.idUser_emettre = u.idUser',
                    array('loginUser','emailUser','MD5(LOWER(TRIM(emailUser))) as emailMD5'))
            //->where('m.idMessage_reponse=?',$idMessage)
            ->where('m.idProfil=1')                         //seuls les messages organisateurs
            ->where('m.idMessage_reponse IS NULL')          //ne pas prendre les réponses
            ->where('m.idEvent=?',$idEvent)                 //les messages de l'évènement
            ->where('unix_timestamp(m.dateEmissionMsg)<?',$dateRef->toString(Zend_Date::TIMESTAMP))         //les message antérieurs à la date fournie
            ->group('m.idMessage','like','disklike')
            ->order('dateEmissionMsg DESC');     
        //les messages actifs seulement ?
        if (!$showAll) {
            $select->where('estActifMsg=1');              //seuls les messages actifs
        }
        $select->limit($nbItemParPage);
        $result = $this->fetchAll($select);
        Zend_Registry::set('sql',$select->assemble());
        return $result;
        }
     
}

