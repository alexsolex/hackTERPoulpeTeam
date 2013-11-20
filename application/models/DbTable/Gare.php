<?php
/////////////////////////////////////////////////////////////////////
//
//PoulpeTeam 2013 - PauseTer(c)
//
/////////////////////////////////////////////////////////////////////
class Application_Model_DbTable_Gare extends Zend_Db_Table_Abstract
{
    protected $_name = 'gare';
    protected $_rowClass = 'Application_Model_Row_GareRow';
    protected $_rowsetClass = 'Application_Model_Rowset_GareRowset';
    protected $_dependentTables = array('Application_Model_DbTable_Situer');
   
}