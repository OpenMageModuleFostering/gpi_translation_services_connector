<?php
/**
 * News item model
 *
 * @author Magento
 */
class Gpi_Tsc_Model_Tsc extends Mage_Core_Model_Abstract
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('gpi_tsc/configurations');
        $this->_init('gpi_tsc/backgroundjobs');
    }

    /**
     * If object is new adds creation date
     *
     * @return Magentostudy_News_Model_News
     */
}