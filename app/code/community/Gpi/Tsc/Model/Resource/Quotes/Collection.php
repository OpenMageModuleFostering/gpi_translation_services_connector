<?php

class Gpi_Tsc_Model_Resource_Quotes_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('gpi_tsc/quotes');
    }
        
}