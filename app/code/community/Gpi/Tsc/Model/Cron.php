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


include_once($modelPath . DS . 'MagentoGpiClient.php');
include_once($webclientPath . DS . 'BackgroundJob.php');
include_once($webclientPath . DS . 'ClientConfigurationServiceImpl.php');
include_once($webclientPath . DS . 'Constants.php');
include_once($webclientPath . DS . 'ExportBackgroundJob.php');
include_once($webclientPath . DS . 'ImportBackgroundJob.php');
include_once($webclientPath . DS . 'RemoteConnection.php');
include_once($webclientPath . DS . 'XliffReader.php');
include_once($webclientPath . DS . 'XliffWriter.php');

include_once($webclientPath . DS . 'ExportArguments.php');

/**
 * Tsc Cron Model
 *
 * @category   Gpi
 * @package    Gpi_Tsc
 * @author     GPI Development Team <magento@globalizationpartners.com>
 */
class Gpi_Tsc_Model_Cron extends Mage_Core_Model_Abstract {
    protected $clientConfigurationService = null;
	protected $connector = null;
		
	
	private function doExports() {				
		Mage::log("Cron::doExports starting");
		$collection = Mage::getModel('gpi_tsc/backgroundjobs')->getCollection();
		$exportJobs = $collection->addFieldToFilter('job_type', array('eq' => 'Export'))
							 ->load();

		$exporter = new ExportBackgroundJob($this->clientConfigurationService);
		foreach($exportJobs as $job) {
			$params = unserialize($job->getJobParams());
			$params->Plugin = $this->connector;		
			$exporter->run($params);
			$job->delete();
		}
		
		Mage::log("Cron::doExports completed");
	}
	
	private function doImports() {
		Mage::log("Cron::doImports starting");
		$collection = Mage::getModel('gpi_tsc/backgroundjobs')->getCollection();
		$importJobs = $collection->addFieldToFilter('job_type', array('eq' => 'Import'))
							 ->load();

		$importer = new ImportBackgroundJob($this->clientConfigurationService);
		foreach($importJobs as $job) {
			$params = unserialize($job->getJobParams());
			$params->Plugin = $this->connector;
			$importer->run($params);			
			$job->delete();
		}
		Mage::log("Cron::doImports completed");
	}
	
	private function cleanUpQuotes() {
		Mage::log("Cron::cleanUpQuotes starting");
		$collection = Mage::getModel('gpi_tsc/quotes')->getCollection();
		$quotes = $collection->load();
		
		Mage::log("Cron::cleanUpQuotes completed");
	}
	
	private function testImportCmsPage($document)
	{
		return;
		$data = XliffReader::parseData($document->Contents);
		unset($data['page_id']);
		unset($data['creation_time']);
		unset($data['update_time']);
		//var_dump($data);
		echo 'importing CmsPage: id ' . $document->ID . ', lang: ' . $document->Language . ' url-key: ' . $data['identifier'] . '<br/>';
			
		
		$storeId = Utilities::getStoreIdByLanguageCode($document->Language);
		$langPage = Mage::getModel('cms/page')->setStoreId($storeId)->load($data['identifier'], 'identifier');
		$langData = $langPage->getData();
		
		// language version does not exist
		if (!isset($langData['page_id'])) {
			$langPage = Mage::getModel('cms/page');
			$langData = array('stores' => array($storeId));
		}
		//var_dump($langData);		
		
		$langData = array_merge($langData, $data);
		var_dump($langData);
		
		$langPage->setData($langData)->save();
		echo 'Done<br/>';		
	}
	
	private function testImportCmsBlock($document)
	{
		return;
		
		$data = XliffReader::parseData($document->Contents);
		$blockId = $data['block_id'];
		unset($data['block_id']);
		unset($data['creation_time']);
		unset($data['update_time']);
		echo 'import-data....<br/>';
		var_dump($data);
		echo '-------------------<br/>';		
		echo 'importing CmsBlock: id ' . $document->ID . ', lang: ' . $document->Language . ' url-key: ' . $data['identifier'] . '<br/>';
		

		echo 'original-source-block....<br/>';
		$blockData = Mage::getModel('cms/block')->load($blockId)->getData();
		var_dump($blockData);
		echo '-------------------<br/>';		
		
		$storeId = Utilities::getStoreIdByLanguageCode($document->Language);
		$langBlock = Mage::getModel('cms/block')->setStoreId($storeId)->load($data['identifier'], 'identifier');
		$langData = $langBlock->getData();
		
		// language version does not exist
		if (!isset($langData['block_id'])) {
			$langBlock = Mage::getModel('cms/block');
			$langData = array('stores' => array($storeId));
		}
		echo 'lang-only-data....<br/>';
		var_dump($langData);		
		echo '-------------------<br/>';		
		
		$langData = array_merge($langData, $data);
		echo 'final-merged-data....<br/>';
		var_dump($langData);
		echo '-------------------<br/>';		
		
		$langBlock->setData($langData)->save();
		echo 'Done<br/>';
	}
	
	private function testImportCategory($document)
	{
		$data = XliffReader::parseData($document->Contents);
		unset($data['store_id']);
		unset($data['creation_time']);
		unset($data['update_time']);
		//var_dump($data);
		echo 'importing Category: id ' . $document->ID . ', lang: ' . $document->Language . ' entity_id: ' . $data['entity_id'] . '<br/>';
			
		
		$storeId = Utilities::getStoreIdByLanguageCode($document->Language);
		$langCategory = Mage::getModel('catalog/category')->setStoreId($storeId)->load($data['entity_id']);
		$langData = $langCategory->getData();		
		$langData = array_merge($langData, $data);
		var_dump($langData);
		
		$langCategory->setData($langData)->save();
		echo 'Done<br/>';		

	}
	
	private function testImportProduct($document)
	{
		return;
		$data = XliffReader::parseData($document->Contents);
		unset($data['creation_time']);
		unset($data['update_time']);
		//var_dump($data);
		echo 'importing Product: id ' . $document->ID . ', lang: ' . $document->Language . ' entity_id: ' . $data['entity_id'] . '<br/>';
			
		
		$storeId = Utilities::getStoreIdByLanguageCode($document->Language);
		$langProduct = Mage::getModel('catalog/product')->setStoreId($storeId)->load($data['entity_id']);
		$langData = $langProduct->getData();		
		$langData = array_merge($langData, $data);
		var_dump($langData);
		
		$langProduct->setData($langData)->save();
		echo 'Done<br/>';		
	}
	
	private function testImport()
	{
		$quoteId = 249184;
		$requestId = com_create_guid();
		
		$params = array(
						"pQueryIdentifier" => $requestId, 
						"quoteId" => $quoteId, 
						"userName" => 'admin'
						);
						
		$clientConfigurationService = new ClientConfigurationServiceImpl();
		$remoteConnection = new RemoteConnection($clientConfigurationService);
		
		echo 'initiating request for document batch...<br/>';
		while(true) {

			$response = $remoteConnection
						  ->create()
						  ->GetDocuments($params)
						  ->GetDocumentsResult;
			
			$documents = $response->Documents->RemoteDocument;
			foreach ($documents as $document) {
				switch ($document->DocumentType) {
					case MagentoGpiClient::CONTENT_TYPE_CMS_PAGES:
						$this->testImportCmsPage($document);
						break;
					case MagentoGpiClient::CONTENT_TYPE_CMS_BLOCKS:
						$this->testImportCmsBlock($document);
						break;
					case MagentoGpiClient::CONTENT_TYPE_CATEGORIES:
						$this->testImportCategory($document);
						break;
					case MagentoGpiClient::CONTENT_TYPE_PRODUCTS:
						$this->testImportProduct($document);
						break;
				}
			}

			if ($response->LastBatch)
				break;
		}
	}
	
	
    /**
     * Initiates any queued export/import operations
     **/
    public function run()
    {		
		/*$storeId = 3;
		$langPage = Mage::getModel('cms/page')->setStoreId($storeId)->load('no-route', 'identifier');
		$langData = $langPage->getData();
		var_dump($langData);
		return;
		
		$this->testImport();
		return;*/
		
		Mage::log("Cron::run entered");
		$lock = Gpi_Tsc_Model_Lock::getInstance();
		if ($lock->isLocked()) {
			Mage::log("Cron::run lock in use, a previous CRON job is already running.");
		} else {
			Mage::log("Cron::run acquiring lock...");
			$lock->lock();
			
			 try {
				$this->clientConfigurationService = new ClientConfigurationServiceImpl();
				$this->connector = new MagentoGpiClient();

				$this->doExports();				
				$this->doImports();
				$this->cleanUpQuotes();
			} catch (Exception $e) {
				Mage::log($e->getMessage());
			}

			Mage::log("Cron::run releasing lock...");
			$lock->unlock();
		}
		Mage::log("Cron::run exited");
    }
}
