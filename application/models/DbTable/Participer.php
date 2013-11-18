<?php

class Application_Model_DbTable_Participer extends Zend_Db_Table_Abstract
{

    protected $_name = 'participer';
    protected $_rowClass = 'Application_Model_Row_ParticiperRow';
    protected $_rowsetClass = 'Application_Model_Rowset_ParticiperRowset';
    protected $_referenceMap = array(
        'question' => array(
            'columns' => 'idQuestion',
            'refTableClass' => 'Application_Model_DbTable_Question',
            'refColumns' => 'idQuestion'
        ),
        'participant' => array(
            'columns' => 'idParticipant',
            'refTableClass' => 'Application_Model_DbTable_Participant',
            'refColumns' => 'idParticipant'
        )
    );


}
