<?php
include(Mage::getModuleDir('model', 'Gpi_Tsc') . DS . 'Model' . DS . 'MagentoGpiClient.php');

/**
 * News controller
 *
 * @author Magento
 */
class Gpi_Tsc_adminhtml_TscController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Init actions
     *
     * @return Magentostudy_News_Adminhtml_NewsController
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        $this->loadLayout()
             ->_setActiveMenu('tsc/manage')
             ->_addBreadcrumb(
                  Mage::helper('gpi_tsc')->__('Tsc'),
                  Mage::helper('gpi_tsc')->__('Tsc')
              )
             ->_addBreadcrumb(
                  Mage::helper('gpi_tsc')->__('Manage Packages'),
                  Mage::helper('gpi_tsc')->__('Manage Packages')
              )
        ;
        return $this;
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->_title($this->__('Tsc'))
             ->_title($this->__('Manage Packages'));

        $this->_initAction();
        
        $block = $this->getLayout()
        ->createBlock('core/text', 'example-block');

        $this->_addContent($block);
        
        $this->renderLayout();
    }
    
    public function postAction()
    {
        //use Zend_Json_Server()
        $server = new Zend_Json_Server();
        $client = new MagentoGpiClient();
        //Mage::log(print_r($_POST, true));
        
        $server->setClass($client);
    
        $server->handle();
    }

    protected function _isAllowed()
    {
        /*switch ($this->getRequest()->getActionName()) {
            case 'new':
            case 'save':
                return Mage::getSingleton('admin/session')->isAllowed('news/manage/save');
                break;
            case 'delete':
                return Mage::getSingleton('admin/session')->isAllowed('news/manage/delete');
                break;
            default:
                return Mage::getSingleton('admin/session')->isAllowed('news/manage');
                break;
        }*/

        return Mage::getSingleton('admin/session')->isAllowed('tsc/manage');
    }

    /**
     * Filtering posted data. Converting localized data if needed
     *
     * @param array
     * @return array
     */
    protected function _filterPostData($data)
    {
        /*$data = $this->_filterDates($data, array('time_published'));
        return $data;*/
    }

    /**
     * Grid ajax action
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Flush News Posts Images Cache action
     */
    public function flushAction()
    {
        /*
        if (Mage::helper('magentostudy_news/image')->flushImagesCache()) {
            $this->_getSession()->addSuccess('Cache successfully flushed');
        } else {
            $this->_getSession()->addError('There was error during flushing cache');
        }
        */
        $this->_forward('index');
    }
}