<?php
/////////////////////////////////////////////////////////////////////
//
//PoulpeTeam 2013 - PauseTer(c)
//
/////////////////////////////////////////////////////////////////////
class Application_Model_DbTable_Situer extends Zend_Db_Table_Abstract
{

    protected $_name = 'situer';
    protected $_rowClass = 'Application_Model_Row_SituerRow';
    protected $_rowsetClass = 'Application_Model_Rowset_SituerRowset';
    protected $_referenceMap = array(
        'gare' => array(
            'columns' => 'idGare',
            'refTableClass' => 'Application_Model_DbTable_Gare',
            'refColumns' => 'idGare'
        ),
        'partenaire' => array(
            'columns' => 'idPartenaire',
            'refTableClass' => 'Application_Model_DbTable_Partenaire',
            'refColumns' => 'idPartenaire'
        ));
}
