<?php
class Application_Model_DbTable_Gain extends Zend_Db_Table_Abstract
{
    protected $_name = 'gain';
    protected $_rowClass = 'Application_Model_Row_GainRow';
    protected $_rowsetClass = 'Application_Model_Rowset_GainRowset';

    protected $_referenceMap    = array(
        'offrir' => array(
            'columns'           => 'idQuestion',
            'refTableClass'     => 'Application_Model_DbTable_Question',
            'refColumns'        => 'idQuestion'
        ));
}