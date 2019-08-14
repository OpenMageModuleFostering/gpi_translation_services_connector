<?php
 
class Gpi_Tsc_Model_Resource_Backgroundjobs extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('gpi_tsc/backgroundjobs', 'job_id');
    }
}