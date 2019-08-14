<?php
 
class Gpi_Tsc_Model_Resource_Configurations extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('gpi_tsc/configurations','config_id');
    }
}