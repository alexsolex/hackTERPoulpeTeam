<?php

class Application_Model_DbTable_Question extends Zend_Db_Table_Abstract
{
    protected $_name = 'question';
    protected $_rowClass = 'Application_Model_Row_QuestionRow';
    protected $_rowsetClass = 'Application_Model_Rowset_QuestionRowset';
    protected $_dependentTables = array('Application_Model_DbTable_Participer');
}

