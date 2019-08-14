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
 
/**
 * Class used to initiate connections with the remote server
 *
 */
class RemoteConnection {

	private $configuration;
	
	/**
	 * 
	 * @param clientConfig
	 */
	public function __construct($clientConfig) {
		$this->configuration = $clientConfig;
	}
		
	/**
	 * 
	 * @return
	 */
	public function create($params = null) {

		$default = array( 
                // We shall only enable TRACING & EXCEPTION for dev 
                'trace' => 1, 
                'exceptions' => true, 
                'cache_wsdl' => WSDL_CACHE_NONE, 
                'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
            );
			
		if(isset($params)) {
			$default = array_merge($params, $default);
		}

		$tscServerEndPointUrl = $this->configuration->getTscServerEndPoint();
		//"http://tsc.globalizationpartners.info/webservices/rpc.svc?wsdl";

		return new SoapClient($tscServerEndPointUrl, $default);		
	}
}