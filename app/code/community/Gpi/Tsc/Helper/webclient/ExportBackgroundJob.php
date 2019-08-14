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
 
class ExportBackgroundJob extends BackgroundJob {

	public function __construct($clientConfigurationService) {
		parent::__construct($clientConfigurationService);
	}
	
	private function addDocuments($quoteId, $documents, $userName)
	{
		Mage::log("ExportBackgroundJob::addDocuments started");
		
		$params = array(
			'quoteId' => $quoteId, 
			'documents' => array_values($documents), 
			'userName' => $userName
		);		
		/*
		for ($i = 0; $i < 5; $i++) {
			try {
				
				//plugin.Logger.Info("Successfully transferred {0} documents to quote# {1}", documents.Count, quoteID);
				break;
			} catch (Exception $e) {
				//plugin.Logger.Error(ex, "Failed to add documents to quote# {0}, Attempt No: {1}. Will wait ten (10) seconds before re-trying..", quoteID, (i + 1));
				if ($i == 4)
					throw $e;
			}
		}*/
		
		
		$client = $this->remoteConnection->create();
		try {
			Mage::log($documents);
			$result = $client->AddDocuments($params);
			var_dump($result);
		}catch(Exception $ex){
			echo $ex->getMessage();
			Mage::logException($ex);
		}
		
		Mage::log($client->__getLastRequest());
		
		Mage::log("ExportBackgroundJob::addDocuments exited");
	}
	
	private function sendQuoteToGpms($quoteId, $userName, $quickQuote)
	{
		$params = array(
			'quoteId' => $quoteId, 			
			'userName' => $userName,
			'quickQuote' => $quickQuote, 
		);	
		
		$this->remoteConnection
			 ->create()
			 ->SendQuoteToGpms($params);
	}
	
	/**
	 *  
	 */
	protected function doWork() {
        
        Mage::log("ExportBackgroundJob::doWork started");
		
		$context = array();
		$context["nodes"] = $this->args->Nodes;
		$context["quote"] = $this->args->Quote;
		
		try{
			$this->args->Plugin->BeforeExport($context);
			
			$documents = array();
			
			$count = 0;
			$size = 0;
			$breakNow = false;
			$_exportedDocuments = 0;

			$userName = $this->args->UserName;
			$quoteId = $this->args->Quote->ID;
			$quickQuote = $this->args->QuickQuote;

			$paneIds = array_keys($this->args->Nodes);
			foreach($paneIds as $paneId)
			{
				$nextDocument = null;
				for ($i = 0, $l = count($this->args->Nodes[$paneId][0]), $last = $l-1; $i < $l; ++$i ) {
					$documentID = $this->args->Nodes[$paneId][0][$i];
					$doc;
					if ($nextDocument == null) {
						$doc = $this->args->Plugin->GetRemoteDocument($paneId, $documentID, $context);				
					} else {
						$doc = $nextDocument;
						$nextDocument = null;
					}

					++$count;
					++$_exportedDocuments;

					if (!is_null($doc)) {
						$documents[] = $doc;
						$size += strlen($doc['Contents']);
					} 

					$_exportProgress = (($_exportedDocuments / $this->args->TotalCount) * 100);

					if ($i < $last) {//check if next document size will grown up the total size by more than a mb...
						$nextDocument = $this->args->Plugin->GetRemoteDocument($paneId, $this->args->Nodes[$paneId][0][$i + 1], $context);
						$breakNow = ($size + strlen($nextDocument['Contents']) > Constants::MAX_TRANSFER_SIZE_LIMIT);
					}
					
					if (!$breakNow && $count < Constants::MAX_TRANSFER_COUNT_LIMIT && $size - Constants::MAX_TRANSFER_SIZE_LIMIT < 0) {//96 files, 4mb limit or next accumulated bigger than 5mb
						continue;
					}

					//send accumulated documents
					$this->addDocuments($quoteId, $documents, $userName);

					$count = 0;
					$size = 0;
					$breakNow = false;
					$documents = array();
				}
			}

			if (count($documents) > 0) {
				//some documents left to send
				//send accumulated documents
				$this->addDocuments($quoteId, $documents, $userName);
			}

			$this->args->Plugin->BeforeSendQuoteToGpms($context);
			
			$this->sendQuoteToGpms($quoteId, $userName, $quickQuote);

			$this->args->Plugin->AfterExport($context);			
		} catch (Exception $e) {
			$this->args->Plugin->ExportFailed($context, $e);
		}
		//WorkerList::ExportWorkers->remove($this);
		unset($context);
		
		Mage::log("ExportBackgroundJob::doWork exited");
	}
}
