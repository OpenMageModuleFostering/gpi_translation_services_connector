<?php
/**
 * News List admin grid container
 *
 * @author Magento
 */
class Gpi_Tsc_Block_Adminhtml_Tsc extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Block constructor
     */
    public function __construct()
    {
        $this->_blockGroup = 'gpi_tsc';
        $this->_controller = 'adminhtml_tsc';
        $this->_headerText = Mage::helper('gpi_tsc')->__('Manage Packages');

        //Mage::log(print_r('open Gpi_Tsc_Block_adminhtml_Tsc',true));
        parent::__construct();
    }
}