 <?php
 /**
 * News frontend controller
 *
 * @author Magento
 */
class Gpi_Tsc_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * Pre dispatch action that allows to redirect to no route page in case of disabled extension through admin panel
     */
    public function preDispatch()
    {
        parent::preDispatch();
        
        if (!Mage::helper('gpi_tsc')->isEnabled()) {
            $this->setFlag('', 'no-dispatch', true);
            $this->_redirect('noRoute');
        }        
    }
    
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->loadLayout();

        $listBlock = $this->getLayout()->getBlock('tsc.main');

        if ($listBlock) {
            $currentPage = abs(intval($this->getRequest()->getParam('p')));
            if ($currentPage < 1) {
                $currentPage = 1;
            }
            $listBlock->setCurrentPage($currentPage);
        }

        $this->renderLayout();
    }

    /**
     * News view action
     */
    public function viewAction()
    {
        
        //$tscId = $this->getRequest()->getParam('id');
        $tscId = $this->getRequest()->getParam();
        if (!$newsId) {
            return $this->_forward('noRoute');
        }

        // @var $model Magentostudy_News_Model_News 
        $model = Mage::getModel('gpi_tsc/tsc');
        $model->load($tscsId);

        if (!$model->getId()) {
            return $this->_forward('noRoute');
        }

        Mage::register('tsc_item', $model);
        
        //Mage::dispatchEvent('before_news_item_display', array('tsc_item' => $model));

        $this->loadLayout();
        $itemBlock = $this->getLayout()->getBlock('tsc.main');
        
        //if ($itemBlock) {
        //    $listBlock = $this->getLayout()->getBlock('tsc.list');
        //    if ($listBlock) {
        //        $page = (int)$listBlock->getCurrentPage() ? (int)$listBlock->getCurrentPage() : 1;
        //    } else {
        //        $page = 1;
        //    }
        //    $itemBlock->setPage($page);
        //}
        
        $this->renderLayout();
        
    }
}