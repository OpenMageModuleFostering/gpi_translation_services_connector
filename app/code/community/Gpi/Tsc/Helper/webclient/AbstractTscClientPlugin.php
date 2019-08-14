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


$webclientPath = $helperPath . DS . 'webclient';
$sharedDataPath = $helperPath . DS . 'shareddata';

include_once($webclientPath . DS . 'ITscClientPlugin.php');
include_once($webclientPath . DS . 'RemoteConnection.php');
include_once($webclientPath . DS . 'ImportArguments.php');
include_once($webclientPath . DS . 'ExportArguments.php');
include_once($webclientPath . DS . 'WorkerList.php');

include_once($sharedDataPath . DS . 'popo' . DS. 'PojoFactory.php');
include_once($sharedDataPath . DS . 'popo' . DS. 'WorkStatus.php');
include_once($sharedDataPath . DS . 'popo' . DS. 'TreeNode.php');
include_once($sharedDataPath . DS . 'popo' . DS. 'RemoteQuote.php');

/**
 * 
 * @author Kevin
 *
 */

abstract class AbstractTscClientPlugin implements ITscClientPlugin {

	protected $clientConfiguration;	

	protected $remoteConnection;

	protected function __construct($clientConfigurationService) {
		$this->clientConfiguration = $clientConfigurationService;
		$this->remoteConnection = new RemoteConnection($clientConfigurationService);
	}

	public function GetStatuses() {
		$statuses = array();

        foreach (WorkerList::$ExportWorkers as $thread) {
            $status = new WorkStatus();
			$status->setPercentaje($thread->getExportProgress());
			$status->setMessage(sprintf("Exported %d documents", $thread->getExportedDocuments()));
			
			$statuses[] = $status;
		}
		
		foreach (WorkerList::$ImportWorkers as $thread) {
			$status = new WorkStatus();
			$status->setPercentaje($thread->getImportedProgress());
			$status->setMessage(sprintf("Imported %d documents", $thread->getImportedDocuments()));
			
			$statuses[] = $status;
		}
		
		return $statuses;
	}
	
    public abstract function GetChildren($parentNodeID, $sourceLanguage, $paneID);

	public function Import($quoteID, $userName) {
		$arguments = new ImportArguments();
		$arguments->Quote = $this->GetQuoteByID($quoteID);
		$arguments->UserName = $userName;
		$arguments->Plugin = null;

		$backgroundJob = array(
			'job_type' => 'Import',
			'job_params' => serialize($arguments)
		);
        Mage::getModel('gpi_tsc/backgroundjobs')
				->setData($backgroundJob)
				->save();
	}
	
	public function GetConfiguration() {
		
		$c = $this->GetConfigurationInternal();
		$clientConfig = $this->clientConfiguration;
		$c->AuthorizationToken = $clientConfig->getAuthorizationToken();
       
		$c->TscServerEndPoint = $clientConfig->getTscServerEndPoint();
		$languages = $this->remoteConnection->create()->GetLanguagesXlt();
		$c->LanguagesXlt = $languages->GetLanguagesXltResult->LanguageXlt;
        
        return json_encode($c);
	}

	protected abstract function GetConfigurationInternal();

	public function SaveConfiguration($authorizationToken, $tscServerEndPoint) {
		if (null == $authorizationToken || empty($authorizationToken))
			throw new IllegalArgumentException("An invalid authorization token was supplied.");

		if (null == $tscServerEndPoint || empty($tscServerEndPoint))
			throw new IllegalArgumentException("An invalid service endpoint was supplied.");

		$c = $this->clientConfiguration;
		$c->setTscServerEndPoint($tscServerEndPoint);

		try {
			$valid = $this->remoteConnection->create()->validateAuthenticationToken($authorizationToken);
		} catch(Exception $e) {
			throw new IllegalArgumentException("An invalid service endpoint was supplied.");
		}

		if (!$valid)
			throw new IllegalArgumentException("An invalid authorization token was supplied.");
		
		$c->setAuthorizationToken($authorizationToken);
		$c->save();
	}
	
	protected abstract function ConvertTreeNodesToIds($treeRoot);
		
	public abstract function GetRemoteDocument($paneID, $nodeID, $context);
	
	public abstract function SetRemoteDocument($document, $context);
	
	public function BeforeExport($context){}
	
	public function BeforeSendQuoteToGpms($context) {}
	
	public function AfterExport($context) {}
	
	public function ExportFailed($context, $e) {}
	
	public function BeforeImport($context) {}
	
	public function AfterImport($context) {}	
	
	public function ImportFailed($context, $e) {}
	
	public function GetCustomConfiguration() {
		$projectId = $clientConfiguration->getProjectID();
		return $this->remoteConnection->create()->getCustomConfiguration($projectId);
	}

	public function SetCustomConfiguration($customConfiguration) {
		$projectId = $clientConfiguration->getProjectID();
		$this->remoteConnection->create()->setCustomConfiguration($projectId, $customConfiguration);
	}	
	
	public function ListQuotes() {
		$projectId = array('projectId' => $this->clientConfiguration->getProjectID());
        
  	    $response = $this->remoteConnection->create()->ListQuotes($projectId)->ListQuotesResult;
        
        $quotes = array();
        if(isset($response->RemoteQuote)) {
            $quotes = $response->RemoteQuote;
        
            foreach($quotes as $quote)
            {
                $quote->TargetLanguages = array_values($quote->TargetLanguages->string);
            }
        }
 
        return json_encode($quotes);
	}
		

	public function CreateQuote($quoteName, $quoteComments, $sourceLanguage, array $languages, $userName) {
		$projectId = $this->clientConfiguration->getProjectID();

		$strings = array();
		
        foreach ($languages as $language) {
			$strings[] = $language;
		}
        $params = array(
			'projectId' => $projectId, 
			'quoteName' => $quoteName, 
			'quoteComments' => $quoteComments, 
			'sourceLanguage' => $sourceLanguage, 
			'languages' => $strings, 
			'userName' => $userName
		);
        
        $remoteQuote = $this->remoteConnection
							->create()
							->CreateQuote($params)
							->CreateQuoteResult;
        
		return $remoteQuote;
	}
	
	public function DeleteQuote($quoteID, $userName) {
        $params = array('quoteId' => $quoteID, 'userName' => $userName);
		$this->remoteConnection->create()->deleteQuote($params);
	}

	public function CloseQuote($quoteID, $userName) {
        $parmas = array('quoteId'=>$quoteID, 'userName'=>$userName);
		$this->remoteConnection->create()->closeQuote($quoteID, $userName);
	}

	public function ListLogs(){
		$projectId = $clientConfiguration->getProjectID();
		$entries = $this->remoteConnection->create()->listLogs($projectId);
		return $entries->getLogEntry()->toArray(new LogEntry());
	}

    
	
	private function GetQuoteByID($quoteID) {
		$quotes = json_decode($this->ListQuotes());
		foreach ($quotes as $quote){
			
			if ($quote->ID == $quoteID)
				return $quote;
		}
			
		return null;
	}

	public function SendQuoteToGpms($quoteID, $userName, $quickQuote) {
		
		$trees = json_decode($this->ListPackageFiles($quoteID));
		
		$nodes = array();
		$totalCount = 0;
		foreach ($trees as $root)
		{
			Mage::log("processing '$root->ID'...");
			if (!array_key_exists($root->ID, $nodes))
			{
				Mage::log("adding '$root->ID' key to node array...");
				$nodes[$root->ID] = array();
			} else {
				Mage::log("the key '$root->ID' already exists...");
			}
			
			$children = $this->ConvertTreeNodesToIds($root);
			Mage::log("key:'$root->ID', children:'" . print_r($children, true) . "'");
			$totalCount += sizeof($children);
			$nodes[$root->ID][] = $children;				
		}
		
		Mage::log("Nodes:'" . print_r($nodes, true) . "'");

		$arguments = new ExportArguments();
		$arguments->Quote = $this->GetQuoteByID($quoteID);
		$arguments->Nodes = $nodes;
		$arguments->UserName = $userName;
		$arguments->Plugin = null;
		$arguments->TotalCount = $totalCount;
		$arguments->QuickQuote = $quickQuote;		

		$backgroundJob = array(
			'job_type' => 'Export',
			'job_params' => serialize($arguments)
		);
        Mage::getModel('gpi_tsc/backgroundjobs')
				->setData($backgroundJob)
				->save();
		
		Mage::log("SendQuoteToGpms ended");
	}

	public function ListPackageFiles($quoteID) {

		$createParams = array(
			'classmap' => array('Children' => 'TreeNode')
		);


        $params = array('quoteId'=>$quoteID);
		$client = $this->remoteConnection->create($createParams);
		$response = $client->ListPackageFiles($params)->ListPackageFilesResult;
        
        $nodes = array();
        if(isset($response->TreeNode)) {
			
			$nodes = $response->TreeNode;

			foreach ($nodes as $key => $value) {
				$nodes[$key]->Children = $nodes[$key]->Children->TreeNode;
			}
			
		}

		return json_encode($nodes);
	}
	

	/**
	 * @see tsc.webclient.ITscClientPlugin#ModifyPackageFiles(long, String, TreeNode[])
	 */
	public function ModifyPackageFiles($quoteID, $userName, array $files){
		
        $params = array('quoteId'=>$quoteID, 'userName'=>$userName, 'files'=>$files);
        $client = $this->remoteConnection->create();

		$result = $client->modifyPackageFiles($params);
	}

}
