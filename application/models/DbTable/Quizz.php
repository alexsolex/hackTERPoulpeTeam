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
        ),'etre' => array(
            'columns'           => 'idQuestion',
            'refTableClass'     => 'Application_Model_DbTable_Question',
            'refColumns'        => 'idQuestion'
        ));

}

