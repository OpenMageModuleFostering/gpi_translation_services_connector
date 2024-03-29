<?php
/**
 * News Admin helper
 *
 * @author Magento
 */
class Gpi_Tsc_Helper_Admin extends Mage_Core_Helper_Abstract
{
    /**
     * Check permission for passed action
     *
     * @param string $action
     * @return bool
     */
    public function isActionAllowed($action)
    {
        //return Mage::getSingleton('admin/session')->isAllowed('tsc/manage/' . $action);
        return Mage::getSingleton('admin/session')->isAllowed('tsc/manage');
    }
}