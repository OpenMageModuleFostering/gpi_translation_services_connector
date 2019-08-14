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

class ImportBackgroundJob extends BackgroundJob {

	public function __construct($clientConfigurationService) {
		parent::__construct($clientConfigurationService);
	}
	
	/**
	 * 
	 */
	protected function doWork() {
		
		Mage::log("ImportBackgroundJob::doWork started");

		$context = array();
		$context["quote"] = $this->args->Quote;
		
		try {
			$this->args->Plugin->BeforeImport($context);
		
			$requestId = com_create_guid();
		
			$params = array(
								"pQueryIdentifier" => $requestId, 
								"quoteId" => $this->args->Quote->ID, 
								"userName" => $this->args->UserName
							);
		
			while(true) {
	
				$response = $this->remoteConnection
								  ->create()
								  ->GetDocuments($params)
								  ->GetDocumentsResult;
				
				$documents = $response->Documents->RemoteDocument;
				foreach ($documents as $document) {
					$this->args->Plugin->SetRemoteDocument($document, $context);
				}

				if ($response->LastBatch)
					break;
			}

			$this->args->Plugin->AfterImport($context);

		} catch (Exception $e) {
			Mage::log("ImportBackgroundJob::doWork exception");
			$this->args->Plugin->ImportFailed($context, $e);
			Mage::logException($e);
		}
		
		unset($context);
		
		Mage::log("ImportBackgroundJob::doWork exited");
	}
}
