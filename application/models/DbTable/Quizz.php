<?php
/////////////////////////////////////////////////////////////////////
//
//PoulpeTeam 2013 - PauseTer(c)
//
/////////////////////////////////////////////////////////////////////
class Application_Model_DbTable_Quizz extends Zend_Db_Table_Abstract
{
    protected $_name = 'quizz';
    protected $_rowClass = 'Application_Model_Row_QuizzRow';
    protected $_rowsetClass = 'Application_Model_Rowset_QuizzRowset';
    protected $_dependentTables = array('Application_Model_DbTable_Participer');
    protected $_referenceMap    = array(
        'participant' => array(
            'columns'           => 'idParticipant',
            'refTableClass'     => 'Application_Model_DbTable_Participant',
            'refColumns'        => 'idParticipant'
        ),
         'gain' => array(
            'columns'           => 'idGain',
            'refTableClass'     => 'Application_Model_DbTable_Gain',
            'refColumns'        => 'idGain'
        ),
         'gare' => array(
            'columns'           => 'idGare',
            'refTableClass'     => 'Application_Model_DbTable_Gare',
            'refColumns'        => 'idGare'
        ),'partenaire' => array(
            'columns'           => 'idPartenaire',
            'refTableClass'     => 'Application_Model_DbTable_Partenaire',
            'refColumns'        => 'idPartenaire'
        ),'question' => array(
            'columns'           => 'idQuestion',
            'refTableClass'     => 'Application_Model_DbTable_Question',
            'refColumns'        => 'idQuestion'
        ));

    public function getQuizz($tvs) 
    {
//        select 
//	qz.*,qst.*,g.*
//        from quizz qz
//        inner join gare g on g.idGare=qz.idgare
//        inner join question qst on qz.idQuestion =qst.idQuestion
//        inner join gain on gain.idGain = qz.idgain
//        where g.tvs="LEW"
//        and qz.dateDebut is not null
//        and qz.datefin is null
//        order by qz.dateDebut desc
//        limit 1
//        ;
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('qz'=>'quizz'),
                        array('idQuizz','qz.dateDebut','qz.dateFin','qz.estRepondu','qz.idPartenaire','qz.idQuestion','qz.idGain','qz.idParticipant','qz.idGare'))
                ->joinInner(array('g'=>'gare'),
                        'qz.idGare=g.idGare',
                        array('g.uic','g.nomgare','g.region','g.tvs'))
                ->joinInner(array('qst'=>'question'),
                        'qst.idQuestion = qz.idQuestion',
                        array('qst.libelle AS libelleQuestion','qst.reponse','qst.erreur1','qst.erreur2','qst.erreur3','qst.url','qst.type'))
                ->joinInner('gain','gain.idGain = qz.idGain',
                        array('gain.libelle AS libelleGain','gain.information AS infoGain','gain.idPartenaire AS idPartenaireGain'))
                ->joinInner(array('pa'=>'partenaire'),
                        'pa.idPartenaire = qz.idPartenaire',
                        array('pa.nomPartenaire','pa.fbPartenaire','pa.twPartenaire','pa.gooPartenaire','pa.urlPartenaire','pa.logoPartenaire','pa.descPartenaire'))
                
                ->where('g.TVS=?',$tvs) //quizz pour la gare
                ->where('qz.dateDebut IS NOT NULL')
                ->where('qz.dateFin IS NULL')
                ->order('qz.dateDebut DESC');
                //->limit(1);     
        $result = $this->fetchAll($select);
        Zend_Registry::set('sql',$select->assemble());
        return $result;
    }
    
    public function getNewQuizz($tvs) 
    {
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('qz'=>'quizz'),
                        array('idQuizz','qz.dateDebut','qz.dateFin','qz.estRepondu','qz.idPartenaire','qz.idQuestion','qz.idGain','qz.idParticipant','qz.idGare'))
                ->joinInner(array('g'=>'gare'),
                        'qz.idGare=g.idGare',
                        array('g.uic','g.nomgare','g.region','g.tvs'))
                ->joinInner(array('qst'=>'question'),
                        'qst.idQuestion = qz.idQuestion',
                        array('qst.libelle AS libelleQuestion','qst.reponse','qst.erreur1','qst.erreur2','qst.erreur3','qst.url','qst.type'))
                ->joinInner('gain','gain.idGain = qz.idGain',
                        array('gain.libelle AS libelleGain','gain.information AS infoGain','gain.idPartenaire AS idPartenaireGain'))
                ->joinInner(array('pa'=>'partenaire'),
                        'pa.idPartenaire = qz.idPartenaire',
                        array('pa.nomPartenaire','pa.fbPartenaire','pa.twPartenaire','pa.gooPartenaire','pa.urlPartenaire','pa.logoPartenaire','pa.descPartenaire'))
                
                ->where('g.TVS=?',$tvs) //quizz pour la gare
                ->where('qz.dateDebut IS NULL')
                ->where('qz.dateFin IS NULL')
                ->order('qz.idQuizz DESC');
                //->limit(1);     
        $result = $this->fetchAll($select);
        Zend_Registry::set('sql',$select->assemble());
        return $result;
    }
}

