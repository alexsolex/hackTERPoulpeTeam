<?php
class Application_Model_DbTable_Gare extends Zend_Db_Table_Abstract
{
    protected $_name = 'gare';
    protected $_rowClass = 'Application_Model_Row_GareRow';
    protected $_rowsetClass = 'Application_Model_Rowset_GareRowset';

    protected $_referenceMap    = array(
        'appartenir' => array(
            'columns'           => 'idQuestion',
            'refTableClass'     => 'Application_Model_DbTable_Question',
            'refColumns'        => 'idQuestion'
        ));
}