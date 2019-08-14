<?php

public class RemoteConfig  {
	private $projectId;
    private $authorizationUsername;    
    private $authorizationPassword;    

    public function RemoteConfig() {
    }

    public function __construct($projectId, $authorizationUsername, $authorizationPassword) {
    	
    	$this->projectId = $projectId;
    	$this->authorizationUsername = $authorizationUsername;
    	$this->authorizationPassword = $authorizationPassword;
    }


    /**
     * Gets the authorizationUsername value for this RemoteConfig.
     * 
     * @return message
     */
    public function getAuthorizationUsername() {
        return $authorizationUsername;
    }


    /**
     * Sets the authorizationUsername value for this RemoteConfig.
     * 
     * @param message
     */
    public function setAuthorizationUsername($authorizationUsername) {
        $this->authorizationUsername = $authorizationUsername;
    }
    
    /**
     * Gets the authorizationPassword value for this RemoteConfig.
     * 
     * @return message
     */
    public function getAuthorizationPassword() {
        return $authorizationPassword;
    }


    /**
     * Sets the authorizationPassword value for this RemoteConfig.
     * 
     * @param message
     */
    public function setAuthorizationPassword($authorizationPassword) {
        $this->authorizationPassword = $authorizationPassword;
    }


    /**
     * Gets the projectId value for this RemoteConfig.
     * 
     * @return projectId
     */
    public long getProjectId() {
        return $projectId;
    }


    /**
     * Sets the projectId value for this RemoteConfig.
     * 
     * @param projectId
     */
    public function setProjectId($projectId) {
        $this->projectId = $projectId;
    }
}
