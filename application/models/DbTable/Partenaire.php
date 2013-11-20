<?php
class Application_Model_DbTable_Partenaire extends Zend_Db_Table_Abstract
{
    protected $_name = 'partenaire';
    protected $_rowClass = 'Application_Model_Row_PartenaireRow';
    protected $_rowsetClass = 'Application_Model_Rowset_PartenaireRowset';
    protected $_dependentTables = array('Application_Model_DbTable_Situer');
    
}