<?php 

$webclientPath = Mage::getModuleDir('helpers', 'Gpi_Tsc') . DS . 'Helper' . DS . 'webclient';

include_once($webclientPath . DS . 'ClientConfigurationService.php');
include_once($webclientPath . DS . 'Xxtea.php');

class ClientConfigurationServiceImpl implements ClientConfigurationService {
	
	private $_projectId;
	private $_authorizationToken;
	private $_tscServerEndPoint;
	private $_authorizationUsername;
	private $_authorizationPassword;
	
	public function __construct() {
		$this->load();
	}
	
	/* (non-Javadoc)
	 * @see tsc.webclient.ClientConfiguration#getAuthorizationToken()
	 */
	public function getAuthorizationToken() {
        return $this->_authorizationToken;
 	}
	
	/* (non-Javadoc)
	 * @see tsc.webclient.ClientConfiguration#setAuthorizationToken(java.lang.String)
	 */
	public function setAuthorizationToken($authorizationToken) {
        
        $plaintext = Xxtea::decrypt(base64_decode($authorizationToken), "Gl0b4l12a7i0n");

        $r = json_decode($plaintext);

        $this->_authorizationToken = '';
        $this->_projectId = '';
        $this->_authorizationUsername = '';
        $this->_authorizationPassword = '';
        
        if(is_object($r)) {
            $this->_authorizationToken = $authorizationToken;
		    $this->_projectId = $r->ProjectID;
		    $this->_authorizationUsername = $r->AuthorizationUsername;
		    $this->_authorizationPassword = $r->AuthorizationPassword;
        }
	}
	
	/* (non-Javadoc)
	 * @see tsc.webclient.ClientConfiguration#getTscServerEndPoint()
	 */
	public function getTscServerEndPoint() {
        return $this->_tscServerEndPoint;
	}
	
	/* (non-Javadoc)
	 * @see tsc.webclient.ClientConfiguration#setTscServerEndPoint(java.lang.String)
	 */
	public function setTscServerEndPoint($tscServerEndPoint) {
        
        //$plaintext = Xxtea::decrypt(base64_decode($tscServerEndPoint), "Gl0b4l12a7i0n");
		//$this->_tscServerEndPoint = json_decode($plaintext);
        
        $this->_tscServerEndPoint = $tscServerEndPoint;
	}
	
	/* (non-Javadoc)
	 * @see tsc.webclient.ClientConfiguration#getProjectID()
	 */
	public function getProjectID() {
		//return 174481;
        return $this->_projectId;
	}
	
	/* (non-Javadoc)
	 * @see tsc.webclient.ClientConfiguration#getAuthorizationUsername()
	 */
	public function getAuthorizationUsername() {
		return $this->_authorizationUsername;
	}
	
	/* (non-Javadoc)
	 * @see tsc.webclient.ClientConfiguration#getAuthorizationPassword()
	 */
	public function getAuthorizationPassword() {
		return $this->_authorizationPassword;
	}
	
	private function load() {

		//Mage::log(print_r(Mage::getModel('gpi_tsc/configurations'),true));
        $collection = Mage::getModel('gpi_tsc/configurations')->getCollection();
        
        //Mage::log(print_r($collection, true));
        $result = $collection->addFieldToFilter('config_name', array('eq' => 'serviceUrl'))->load();
        $infoArray = $result->toArray();
        $this->_tscServerEndPoint = $infoArray['items'][0]['config_value'];
        
        $collection = Mage::getModel('gpi_tsc/configurations')->getCollection();
        $result = $collection->addFieldToFilter('config_name', array('eq' => 'authToken'))->load();
        $infoArray = $result->toArray();
        $authorizationToken = $infoArray['items'][0]['config_value'];
        $this->setAuthorizationToken($authorizationToken);
	}
	
	/* (non-Javadoc)
	 * @see tsc.webclient.ClientConfiguration#save()
	 */
	public function save() {
        
        $this->updateConfiguration('serviceUrl', $this->_tscServerEndPoint);
        $this->updateConfiguration('authToken', $this->_authorizationToken);
        
        $this->load();
	}
    
    public function updateConfiguration($name, $value) {
        
        $model = Mage::getModel('gpi_tsc/configurations')->getCollection()
                        ->addFieldToFilter('config_name', array('eq' => $name))
                        ->getFirstItem();

        $data = array(
            'config_name' => $name,
            'config_value' => $value,
        );
        
        $model->addData($data)->setId($model->config_id)->save();
        
    }
}
