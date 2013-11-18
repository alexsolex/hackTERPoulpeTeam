<?php
class Application_Model_DbTable_Partenaire extends Zend_Db_Table_Abstract
{
    protected $_name = 'partenaire';
    protected $_rowClass = 'Application_Model_Row_PartenaireRow';
    protected $_rowsetClass = 'Application_Model_Rowset_PartenaireRowset';

    protected $_referenceMap    = array(
        'poser' => array(
            'columns'           => 'idQuestion',
            'refTableClass'     => 'Application_Model_DbTable_Question',
            'refColumns'        => 'idQuestion'
        ),
        'proposer' => array(
            'columns'           => 'idGain',
            'refTableClass'     => 'Application_Model_DbTable_Gain',
            'refColumns'        => 'idGain'
        ));
}