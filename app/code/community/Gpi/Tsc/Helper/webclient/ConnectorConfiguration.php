<?php

/**
 * 
 *
 */
final class ConnectorConfiguration {

	/**
	 *  Current Loged in CMS User
	 */
	public $UserName;
	
	/**
	 *  Array of string of enabled languages in magento
	 */
	public $SourceLanguages = array();
	
	/**
	 *  Array of string of enabled languages in magento
	 */
	public $TargetLanguages = array();
	
	/**
	 *  
	 */
	public $DocumentBrowserPanes = array("content","Contents");
	
	/**
	 * 
	 */
	public $LanguagesXlt = array();
	
	/**
	 * 
	 */
	public $AuthorizationToken;
	
	/**
	 * 
	 */
	public $TscServerEndPoint;
	
}