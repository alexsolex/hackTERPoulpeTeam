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
    
    public function getGareByTvs($tvs) {
        $select = $this->select()->where("tvs = ?",$tvs);
        return $this->fetchRow($select);
    }
    
     
    public function get3ByNomGare($nomGare) {
        $select = $this->select()
                ->from('gare','nomgare')
                ->where('nomgare != ?',$nomGare);
        $result = $this->fetchAll($select);
        return $result;
    }
    public function get3ByCodePostal($codepostal) {
        $select = $this->select()
                ->from('gare','nom')
                ->where('nomGare != ?',$nomGare);
                
                
        $result = $this->fetchAll($select);
        return $result;
    }
    
  
}