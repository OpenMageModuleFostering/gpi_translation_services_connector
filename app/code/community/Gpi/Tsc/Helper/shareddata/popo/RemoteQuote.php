<?php

class RemoteQuote {
	
	protected $createdBy;
    protected $ID;
    protected $lastModifiedBy;
    protected $lastModifiedOn;
    protected $name;
    protected $notes;
    protected $packageCount;
    protected $portalLink;
    protected $sourceLanguage;
    protected $sourceLanguageIsoCode;
    protected $status;
    protected $targetLanguages = array();
	
	public function __construct(){
		
	}
	

	/**
     * Gets the createdBy value for this RemoteQuote.
     * 
     * @return createdBy
     */
    public function getCreatedBy() {
        return $createdBy;
    }


    /**
     * Sets the createdBy value for this RemoteQuote.
     * 
     * @param createdBy
     */
    public function setCreatedBy($createdBy) {
        $this->createdBy = $createdBy;
    }


    /**
     * Gets the ID value for this RemoteQuote.
     * 
     * @return ID
     */
    public function getID() {
        return $ID;
    }


    /**
     * Sets the ID value for this RemoteQuote.
     * 
     * @param ID
     */
    public function setID($id) {
        $this->ID = $id;
    }


    /**
     * Gets the lastModifiedBy value for this RemoteQuote.
     * 
     * @return lastModifiedBy
     */
    public function getLastModifiedBy() {
        return $lastModifiedBy;
    }


    /**
     * Sets the lastModifiedBy value for this RemoteQuote.
     * 
     * @param lastModifiedBy
     */
    public function setLastModifiedBy($lastModifiedBy) {
        $this->lastModifiedBy = $lastModifiedBy;
    }


    /**
     * Gets the lastModifiedOn value for this RemoteQuote.
     * 
     * @return lastModifiedOn
     */
    public function getLastModifiedOn() {
        return $lastModifiedOn;
    }


    /**
     * Sets the lastModifiedOn value for this RemoteQuote.
     * 
     * @param lastModifiedOn
     */
    public function setLastModifiedOn($lastModifiedOn) {
        $this->lastModifiedOn = $lastModifiedOn;
    }


    /**
     * Gets the name value for this RemoteQuote.
     * 
     * @return name
     */
    public function getName() {
        return $name;
    }


    /**
     * Sets the name value for this RemoteQuote.
     * 
     * @param name
     */
    public function setName($name) {
        $this->name = $name;
    }


    /**
     * Gets the notes value for this RemoteQuote.
     * 
     * @return notes
     */
    public function getNotes() {
        return $notes;
    }


    /**
     * Sets the notes value for this RemoteQuote.
     * 
     * @param notes
     */
    public function setNotes($notes) {
        $this->notes = $notes;
    }


    /**
     * Gets the packageCount value for this RemoteQuote.
     * 
     * @return packageCount
     */
    public function getPackageCount() {
        return $packageCount;
    }


    /**
     * Sets the packageCount value for this RemoteQuote.
     * 
     * @param packageCount
     */
    public function setPackageCount($packageCount) {
        $this->packageCount = $packageCount;
    }


    /**
     * Gets the portalLink value for this RemoteQuote.
     * 
     * @return portalLink
     */
    public function getPortalLink() {
        return $portalLink;
    }


    /**
     * Sets the portalLink value for this RemoteQuote.
     * 
     * @param portalLink
     */
    public function setPortalLink($portalLink) {
        $this->portalLink = $portalLink;
    }


    /**
     * Gets the sourceLanguage value for this RemoteQuote.
     * 
     * @return sourceLanguage
     */
    public function getSourceLanguage() {
        return $sourceLanguage;
    }


    /**
     * Sets the sourceLanguage value for this RemoteQuote.
     * 
     * @param sourceLanguage
     */
    public function setSourceLanguage($sourceLanguage) {
        $this->sourceLanguage = $sourceLanguage;
    }
    
    /**
     * Gets the targetLanguages value for this RemoteQuote.
     * 
     * @return targetLanguages
     */
    public function getTargetLanguages() {
        return $targetLanguages;
    }


    /**
     * Sets the targetLanguages value for this RemoteQuote.
     * 
     * @param targetLanguages
     */
    public function setTargetLanguages($targetLanguages) {
        $this->targetLanguages = $targetLanguages;
    }

    
    /**
     * Gets the sourceLanguageIsoCode value for this RemoteQuote.
     * 
     * @return sourceLanguageIsoCode
     */
    public function getSourceLanguageIsoCode() {
        return $sourceLanguageIsoCode;
    }


    /**
     * Sets the sourceLanguageIsoCode value for this RemoteQuote.
     * 
     * @param sourceLanguageIsoCode
     */
    public function setSourceLanguageIsoCode($sourceLanguageIsoCode) {
        $this->sourceLanguageIsoCode = $sourceLanguageIsoCode;
    }

    /**
     * Gets the status value for this RemoteQuote.
     * 
     * @return status
     */
    public function getStatus() {
        return $status;
    }


    /**
     * Sets the status value for this RemoteQuote.
     * 
     * @param status
     */
    public function setStatus($status) {
        $this->status = $status;
    }
}
