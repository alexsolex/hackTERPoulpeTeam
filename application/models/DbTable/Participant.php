<?php
/////////////////////////////////////////////////////////////////////
//
//PoulpeTeam 2013 - PauseTer(c)
//
/////////////////////////////////////////////////////////////////////
class Application_Model_DbTable_Participant extends Zend_Db_Table_Abstract
{
    protected $_name = 'participant';
    protected $_rowClass = 'Application_Model_Row_ParticipantRow';
    protected $_rowsetClass = 'Application_Model_Rowset_ParticipantRowset';
    protected $_dependentTables = array('Application_Model_DbTable_Participer');
   
}

