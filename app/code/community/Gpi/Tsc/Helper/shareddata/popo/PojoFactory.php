<?php

class PojoFactory {
	
    /*
    * ArrayOfRemoteQuote
    */
	public static function toArray($arrayOfRemoteQuote){
		$remoteQuotes = array();
		//Iterator<org.datacontract.schemas._2004._07.tsc_shareddata.RemoteQuote> $iter = $arrayOfRemoteQuote->getRemoteQuote().iterator();
		foreach($arrayOfRemoteQuote as $key => $value) {
			$remoteQuote = toPojo($value);
			$remoteQuotes[] = $value;
		}
		
		return $remoteQuotes;
	}

	public static function toPojo($src) {
		$remoteQuote = new RemoteQuote();
		
		$remoteQuote->setCreatedBy($src->getCreatedBy()->getValue());
		$remoteQuote->setID($src->getID().longValue());
		$remoteQuote->setLastModifiedBy($src->getLastModifiedBy()->getValue());
		$remoteQuote->setLastModifiedOn(jdtogregorian($src->getLastModifiedOn()));
		$remoteQuote->setName($src->getName()->getValue());
		$remoteQuote->setNotes($src->getNotes()->getValue());
		$remoteQuote->setPackageCount($src->getPackageCount().intValue());
		$remoteQuote->setPortalLink($src->getPortalLink()->getValue());
		$remoteQuote->setSourceLanguage($src->getSourceLanguage()->getValue());
		$remoteQuote->setSourceLanguageIsoCode($src->getSourceLanguageISOCode()->getValue());
		$remoteQuote->setStatus($src->getStatus()->value());
		$remoteQuote->setTargetLanguages($src->getTargetLanguages()->getValue());
		
		return remoteQuote;
	}
	
    /*
	public static function toArray($arrayOfTreeNode){
		$treeNodes = array();
		$iter = $arrayOfTreeNode->getTreeNode()->getIterator();
		
        while($iter->hasNext()) {
			$treeNode = toPojo(next($iter));
			$treeNodes[] = $treeNode;
		}
		
		return $treeNodes.toArray(new TreeNode[0]);
	}
	
	public static function toPojo($src) {
		$treeNode = array();
		
		$treeNode->setID($src->getID()->getValue());
		$treeNode->setContentType($src->getContentType().value());
		$treeNode->setSelected($src->isSelected());
		$treeNode->setName($src->getName()->getValue());
		$treeNode->setLastModificationDate(jdtogregorian($src->getLastModificationDate()));
		
		$arrayOfTreeNode = $src->getChildren()->getValue();
		if (null != $arrayOfTreeNode) {
			$treeNode->setChildren(toArray($arrayOfTreeNode));
		}
		
		return $treeNode;
	}*/
	
    /*
    *   TreeNode $src
    */
    
	public static function fromPojo($src) {
		//org.datacontract.schemas._2004._07.tsc_shareddata.TreeNode 
        $treeNode = new org.datacontract.schemas._2004._07.tsc_shareddata.TreeNode();
		
		$factory = new ObjectFactory();//TODO: To check this class
		
		$treeNode->setID($factory->createTreeNodeID($src->getID()));
		$treeNode->setContentType(TreeContentType.fromValue($src->getContentType()));
        //TODO: to change TreeContentType funtionality
        
		$treeNode->setSelected($src->getSelected());
		$treeNode->setName($factory->createTreeNodeName($src->getName()));
		

		$calendar = $src->getLastModificationDate();
		if (null != $calendar) {
			$gCal = new GregorianCalendar();
			$gCal.setTimeInMillis($calendar.getTimeInMillis());//TODO: to check Calendar PHP functionality
			try {
				$cal = DatatypeFactory.newInstance().newXMLGregorianCalendar($gCal);//TODO: to check Calendar PHP functionality
				$treeNode->setLastModificationDate($cal);
			} catch (Exception $e) {
				
			}
		}
		
		$children = $src.getChildren();
		if (null != $children) {
			$treeNodes = new ArrayOfTreeNode();
			foreach($children as $node){
				$treeNodes->getTreeNode().add(fromPojo($node));//TODO: To check functionality
			}
			$treeNode->setChildren($factory->createTreeNodeChildren($treeNodes));
		}
		
		return $treeNode;
	}
}
