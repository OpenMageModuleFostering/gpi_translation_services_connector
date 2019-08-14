<?php
/**
 * Globalization Partners International
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@globalizationpartners.com so we can send you a copy immediately.
 *
 * @category    Gpi
 * @package     Gpi_Tsc
 * @copyright  Copyright (c) 2015 Globalization Partners International, LLC. (http://www.globalizationpartners.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 
$helperPath = Mage::getModuleDir('helpers', 'Gpi_Tsc') . DS . 'Helper';
$modelPath = Mage::getModuleDir('Model', 'Gpi_Tsc') . DS . 'Model';

$webclientPath = $helperPath . DS . 'webclient';
$sharedDataPath = $helperPath . DS . 'shareddata';

include_once($modelPath . DS . 'Utilities.php');

include_once($webclientPath . DS . 'AbstractTscClientPlugin.php');
include_once($webclientPath . DS . 'ClientConfigurationServiceImpl.php');
include_once($webclientPath . DS . 'ConnectorConfiguration.php');

class MagentoGpiClient extends AbstractTscClientPlugin {

    const CONTENT_TYPE_CMS_PAGES = 'CmsPages';
    const CONTENT_TYPE_CMS_BLOCKS = 'CmsBlocks';
    const CONTENT_TYPE_CATEGORIES = 'Categories';
    const CONTENT_TYPE_PRODUCTS = 'Products';
    
    public function __construct() {
        parent::__construct(new ClientConfigurationServiceImpl());
    }
    
    public function GetChildren($parentNodeID, $sourceLanguage, $paneID) {
     
        $children = array();
        $storeId = Utilities::getStoreIdByLanguageCode($sourceLanguage);
		
        switch ($paneID) {
            case self::CONTENT_TYPE_CMS_PAGES:
                $children = Utilities::GetCMSPages($storeId);
                break;
            case self::CONTENT_TYPE_CMS_BLOCKS:
                $children = Utilities::GetCMSBlocks($storeId);
                break;
            case self::CONTENT_TYPE_CATEGORIES:
                $children = Utilities::GetCategories($storeId);
                break;
            case self::CONTENT_TYPE_PRODUCTS:
                $children = Utilities::GetProducts($storeId);
                break;
        }
        
        return json_encode($children);
        
    }
    
    protected function GetConfigurationInternal() {
        
        $config = new ConnectorConfiguration(); 
        
		//$locale = Mage::getModel('core/locale');
		//$languages = $locale->getOptionLocales();
		
        //$languagesList = array();
		//foreach($languages as $language) {
        //    $languagesList[] = array('ID' => $language['value'], 'Name' => $language['label']);
        //}
		
		
		$stores = Mage::getModel('core/store')->getCollection();
		$locale = Mage::getModel('core/locale');
		$languages = $locale->getOptionLocales();
		$storeLanguages = array_column($languages, 'label', 'value');
				
		foreach($stores as $store)
		{
			$locale_code = Mage::getStoreConfig('general/locale/code', $store->getId());
			$country_name = $storeLanguages[$locale_code];
			$languagesList[] = array('ID' => $store->getId(), 'Name' => sprintf('%s [%s]', $store->getName(), $country_name));
		}
		
        

        $config->SourceLanguages = $languagesList;
        $config->TargetLanguages = $languagesList;
        
        $documentBrowserPanes = array();
        $documentBrowserPanes[] = array('ID'=>self::CONTENT_TYPE_CMS_PAGES, 'Name'=>'CMS Pages');
        $documentBrowserPanes[] = array('ID'=>self::CONTENT_TYPE_CMS_BLOCKS, 'Name'=>'CMS Blocks');
        $documentBrowserPanes[] = array('ID'=>self::CONTENT_TYPE_CATEGORIES, 'Name'=>'Categories');
        $documentBrowserPanes[] = array('ID'=>self::CONTENT_TYPE_PRODUCTS, 'Name'=>'Products');
        
        $config->DocumentBrowserPanes = $documentBrowserPanes;
        
        $config->UserName = Mage::getSingleton('admin/session')->getUser()->getUsername();
        return $config;
    }
    
    protected function ConvertTreeNodesToIds($treeRoot) {
    	//check only parents checked if threy are folders with children  
		
		$ids = array();
		foreach ($treeRoot->Children as $node)
		{
			$ids[] = $node->ID;
		}

		return $ids;
    }
	
	public function CreateQuote($quoteName, $quoteComments, $sourceStoreId, array $storeIds, $userName) {
		
		$storeLanguages = array();
		$target = array();
		foreach ($storeIds as $storeId) {
			$storeLanguage = Utilities::getStoreLanguage($storeId);
			if (!in_array($storeLanguage, $storeLanguages)) {
				$storeLanguages[] = $storeLanguage;
				$target[$storeLanguage] = $storeId;
			}
		}
		
		$sourceStoreLanguage = Utilities::getStoreLanguage($sourceStoreId);
		$remoteQuote = parent::CreateQuote($quoteName, $quoteComments, $sourceStoreLanguage, $storeLanguages, $userName);
		
		$source = array();
		$source[$sourceStoreLanguage] = $sourceStoreId;
		
		$quoteMeta = array(
			'source' => $source,
			'target' => $target
		);
		
		$quoteData = array(
			'quote_id' => $remoteQuote->ID,
			'quote_meta' => serialize($quoteMeta)
		);
		
		Mage::getModel('gpi_tsc/quotes')
				->setData($quoteData)
				->save();
		
		return json_encode($remoteQuote);
	}
		
	public function GetRemoteDocument($paneID, $nodeID, $context) {
		$remoteDocument = array();
		
		switch ($paneID) {
            case self::CONTENT_TYPE_CMS_PAGES:
                $remoteDocument = Utilities::ExportCMSPage($nodeID, $context);
                break;
            case self::CONTENT_TYPE_CMS_BLOCKS:
                $remoteDocument = Utilities::ExportCMSBlock($nodeID, $context);
                break;
            case self::CONTENT_TYPE_CATEGORIES:
                $remoteDocument = Utilities::ExportCategory($nodeID, $context);
                break;
            case self::CONTENT_TYPE_PRODUCTS:
                $remoteDocument = Utilities::ExportProduct($nodeID, $context);
                break;
        }
		
		return $remoteDocument;
    }
	
	public function SetRemoteDocument($document, $context) {
		switch ($document->DocumentType) {
            case self::CONTENT_TYPE_CMS_PAGES:
                Utilities::ImportCMSPage($document, $context);
                break;
            case self::CONTENT_TYPE_CMS_BLOCKS:
                Utilities::ImportCMSBlock($document, $context);
                break;
            case self::CONTENT_TYPE_CATEGORIES:
                Utilities::ImportCategory($document, $context);
                break;
            case self::CONTENT_TYPE_PRODUCTS:
                Utilities::ImportProduct($document, $context);
                break;
        }
    }
}