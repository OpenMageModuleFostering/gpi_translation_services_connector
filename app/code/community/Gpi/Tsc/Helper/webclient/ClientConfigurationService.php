<?php

/**
 * 
 *
 */
interface ClientConfigurationService {

	/**
	 * 
	 * @return
	 */
	public function getAuthorizationToken();

	/**
	 * 
	 * @param authorizationToken
	 */
	public function setAuthorizationToken($authorizationToken);

	/**
	 * 
	 * @return
	 */
	public function getTscServerEndPoint();

	/**
	 * 
	 * @param tscServerEndPoint
	 */
	public function setTscServerEndPoint($tscServerEndPoint);

	/**
	 * 
	 * @return
	 */
	public function getProjectID();

	/**
	 * 
	 * @return
	 */
	public function getAuthorizationUsername();

	/**
	 * 
	 * @return
	 */
	public function getAuthorizationPassword();

	/**
	 * 
	 */
	public function save();

}