<?php
/**
 * Public interface that exposes client-specific method calls
 */
interface ITscClientPlugin {

    /**
     * Lists all the statuses (if any) for any running background worker (both Import and Export)
     */
    public function GetStatuses();

    /**
     * Lists all the children nodes that have pParentNodeID as parent
     * @param parentNodeID The parent node ID
     * @param sourceLanguage Source language used to filter possible nodes
     * @param paneID The ID of the pane that requested the nodes
     * @return A collection of TreeNodes, or an empty list if the node does not has any children 
     */
    public function GetChildren($parentNodeID, $sourceLanguage, $paneID);

    /**
     * Imports the translated document(s) to the specified quote.
     * @param quoteID The quote ID.
     * @param userName The name of the user doing the operation
     */		  
    public function Import($quoteID, $userName);


    /**
     * Gets the connector configuration.
     * @return A ConnectorConfiguration object
     */		 
    public function GetConfiguration();

    /**
     * Gets the custom configuration.
     * @return A String that the client has to deserialize in order to get the custom configuration values
     */		 
    public function GetCustomConfiguration();

    /**
     * Sets the custom configuration.
     * @param customConfiguration The custom configuration serialized as a String.
     */		  
    public function SetCustomConfiguration($customConfiguration);


    /**
     * Saves the configuration.
     * @param authorizationToken The authorization token. This token is encrypted and contains the user/password and project id
     * @param tscServerEndPoint The TSC server end point.
     */		 
    function SaveConfiguration($authorizationToken, $tscServerEndPoint);

    /**
     * 
     * @return
     */
    public function ListQuotes();

    /**
     * 
     * @param quoteName
     * @param quoteComments
     * @param sourceLanguage
     * @param languages
     * @param userName
     * @return
     */
    public function CreateQuote($quoteName, $quoteComments, $sourceLanguage, string $languages, $userName);

    /**
     * 
     * @param quoteID
     * @param userName
     */
    public function DeleteQuote($quoteID, $userName);

    /**
     * 
     * @param quoteID
     * @param userName
     */
    public function CloseQuote($quoteID, $userName);

    /**
     * 
     * @return
     */
    public function ListLogs();

    /**
     * 
     * @param quoteID
     * @param userName
     * @param quickQuote
     */
    public function SendQuoteToGpms($quoteID, $userName, $quickQuote);

    /**
     * 
     * @param quoteID
     * @return
     */
    public function ListPackageFiles($quoteID);

    /**
     * 
     * @param quoteID
     * @param userName
     * @param files
     */
    public function ModifyPackageFiles($quoteID, $userName, string $files);		
}