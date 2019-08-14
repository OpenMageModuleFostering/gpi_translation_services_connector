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

class Utilities {

	public static function getStoreLanguage($storeId) {
		$storeLanguage = Mage::getStoreConfig('general/locale/code', $storeId);
		$storeLanguage = str_replace("_", "-", $storeLanguage);
		return $storeLanguage;
	}
	
	public static function getStoreIdByLanguageCode($languageCode) {
		$stores = Mage::app()->getStores();
		foreach ($stores as $key => $val)  {
			$storeId = Mage::app()->getStore($key)->getId();
			$storeLanguage = Utilities::getStoreLanguage($storeId);
			if ($storeLanguage == $languageCode) {
				return $storeId;
			}
		}
		return 0;
	}

	public static function getSourceLanguageStoreIdFromQuote($quoteId) {
		$quoteModel = Mage::getModel('gpi_tsc/quotes')->load($quoteId);		
		$quoteMeta = unserialize($quoteModel->QuoteMeta);
		$storeId = reset($quoteMeta['source']);
		return $storeId;
	}
		
    public static function GetCMSPages ($storeId) {
		
        $pages = Mage::getModel('cms/page')
						->getCollection()
						->addStoreFilter($storeId)						
						->addFieldToFilter('is_active', 1);
        
        $collection = array();
        
        foreach ($pages as $page)
        {                  
            $data = $page->getData();
			
			$node = new TreeNode();
            $node->ID = $data['page_id'];
            $node->Name = $data['title'];
            $node->LastModificationDate = $data['update_time'];
            $collection[] = $node;
        }
        
        return $collection;
    }
    
    public static function GetCMSBlocks ($storeId) {

        $blocks = Mage::getModel('cms/block')
						->getCollection()
						->addStoreFilter($storeId)
						->addFieldToFilter('is_active', 1);
        
        $collection = array();
        
        foreach ($blocks as $block)
        {                  
            $node = new TreeNode();
            $data = $block->getData();
            $node->ID = $data['identifier'];
            $node->Name = $data['title'];
            $node->LastModificationDate = $data['update_time'];
            $collection[] = $node;
        }
        
        return $collection;
    }

    public static function GetProducts ($storeId) {

        $products =  Mage::getModel('catalog/product')
						->setStoreId($storeId)
						->getCollection()
						->addAttributeToSelect('*');
        
        $collection = array();        
        foreach ($products as $product)
        {                  
            $node = new TreeNode();
            $node->Name = $product->getName();
            $node->ID = $product->getId();
            $collection[] = $node;
        }
        
        return $collection;
    }

    public static function GetCategories ($storeId) {
		
		$rootCategoryId = Mage::app()->getStore($storeId)->getRootCategoryId();
		$categories = Mage::getModel('catalog/category')
								->getCollection()
								->setStoreId($storeId)
								->addFieldToFilter('is_active', 1)
								->addAttributeToFilter('path', array('like' => "1/{$rootCategoryId}/%"))
								->addAttributeToSelect('*');
		
		$collection = array();
		foreach ($categories as $category) {
			$categ = new TreeNode();
			$categ->ID = $category->getId();
			$categ->Name = $category->getName();
			$collection[] = $categ;
		}
		
		return $collection;
    }

	public static function ExportCMSPage ($nodeID, $context) {
		
		$quote = $context['quote'];
		$storeId = Utilities::getSourceLanguageStoreIdFromQuote($quote->ID);
		$page = Mage::getModel('cms/page')->setStoreId($storeId)->load($nodeID);
		$data = $page->getData();
		
		$doc = array();
		$doc['ID'] = $data['page_id'];
		$doc['Title'] = $data['title'];
		$doc['DocumentType'] = MagentoGpiClient::CONTENT_TYPE_CMS_PAGES;
		$doc['LastModified'] = date("Y-m-d\Th:i:s\.u\Z");
		$doc['FileName'] = sprintf("%s.xml", $data['page_id']);
		
		$xliff = new XliffWriter();
		foreach ($data as $key => $value) {
			if (!is_array($value) && !is_null($value)) {
				$xliff->addTransUnit($key, $value);
			}
		}		
		$doc['Contents'] = $xliff->getDocument();
		return $doc;
	}
	
	public static function ExportCMSBlock ($nodeID, $context) {

		$quote = $context['quote'];
		$storeId = Utilities::getSourceLanguageStoreIdFromQuote($quote->ID);
		$block = Mage::getModel('cms/block')->setStoreId($storeId)->load($nodeID);
		$data = $block->getData();
		
		$doc = array();
		$doc['ID'] = $data['block_id'];
		$doc['Title'] = $data['title'];
		$doc['DocumentType'] = MagentoGpiClient::CONTENT_TYPE_CMS_BLOCKS;
		$doc['LastModified'] = date("Y-m-d\Th:i:s\.u\Z");
		$doc['FileName'] = sprintf("%s.xml", $data['block_id']);
		
		$xliff = new XliffWriter();
		foreach ($data as $key => $value) {
			if (!is_array($value) && !is_null($value)) {
				$xliff->addTransUnit($key, $value);
			}
		}		
		$doc['Contents'] = $xliff->getDocument();
		return $doc;
	}
	
	public static function ExportProduct ($nodeID, $context) {
		
		$quote = $context['quote'];
		$storeId = Utilities::getSourceLanguageStoreIdFromQuote($quote->ID);
		$product = Mage::getModel('catalog/product')->setStoreId($storeId)->load($nodeID);
		$data = $product->getData();

		$doc = array();
		$doc['ID'] = $data['entity_id'];
		$doc['Title'] = $data['name'];
		$doc['DocumentType'] = MagentoGpiClient::CONTENT_TYPE_PRODUCTS;
		$doc['LastModified'] = date("Y-m-d\Th:i:s\.u\Z");
		$doc['FileName'] = sprintf("%s.xml", $data['entity_id']);
		
		$xliff = new XliffWriter();
		foreach ($data as $key => $value) {
			if (!is_array($value) && !is_null($value)) {
				$xliff->addTransUnit($key, $value);
			}
		}		
		$doc['Contents'] = $xliff->getDocument();
		return $doc;
    }
	
	public static function ExportCategory ($nodeID, $context) {
		$quote = $context['quote'];
		$storeId = Utilities::getSourceLanguageStoreIdFromQuote($quote->ID);
		$category = Mage::getModel('catalog/category')->setStoreId($storeId)->load($nodeID);
		$data = $category->getData();

		$doc = array();
		$doc['ID'] = $data['entity_id'];
		$doc['Title'] = $data['name'];
		$doc['DocumentType'] = MagentoGpiClient::CONTENT_TYPE_CATEGORIES;
		$doc['LastModified'] = date("Y-m-d\Th:i:s\.u\Z");
		$doc['FileName'] = sprintf("%s.xml", $data['entity_id']);
		
		$xliff = new XliffWriter();
		foreach ($data as $key => $value) {
			if (!is_array($value) && !is_null($value)) {
				$xliff->addTransUnit($key, $value);
			}
		}		
		$doc['Contents'] = $xliff->getDocument();
		return $doc;
    }
	
	public static function ImportCMSPage ($document, $context) {
		
		$data = XliffReader::parseData($document->Contents);
		unset($data['page_id']);
		unset($data['creation_time']);
		unset($data['update_time']);
		
		$storeId = Utilities::getStoreIdByLanguageCode($document->Language);
		$langPage = Mage::getModel('cms/page')
							->setStoreId($storeId)
							->load($data['identifier'], 'identifier');
		$langData = $langPage->getData();
		
		// language version does not exist
		if (!isset($langData['page_id'])) {
			$langPage = Mage::getModel('cms/page');
			$langData = array('stores' => array($storeId));
		}

		$langData = array_merge($langData, $data);
		$langPage->setData($langData)->save();
    }
	
	public static function ImportCMSBlock ($document, $context) {
        
		$data = XliffReader::parseData($document->Contents);
		unset($data['block_id']);
		unset($data['creation_time']);
		unset($data['update_time']);
		
		$storeId = Utilities::getStoreIdByLanguageCode($document->Language);
		$langBlock = Mage::getModel('cms/block')
							->setStoreId($storeId)
							->load($data['identifier'], 'identifier');
		$langData = $langBlock->getData();
		
		// language version does not exist
		if (!isset($langData['block_id'])) {
			$langBlock = Mage::getModel('cms/block');
			$langData = array('stores' => array($storeId));
		}
		
		$langData = array_merge($langData, $data);
		$langBlock->setData($langData)->save();
    }
	
	public static function ImportProduct ($document, $context) {
		
		$data = XliffReader::parseData($document->Contents);
		unset($data['creation_time']);
		unset($data['update_time']);	
		
		$storeId = Utilities::getStoreIdByLanguageCode($document->Language);
		$langProduct = Mage::getModel('catalog/product')->setStoreId($storeId)->load($data['entity_id']);
		$langData = $langProduct->getData();		
		$langData = array_merge($langData, $data);
		
		$langProduct->setData($langData)->save();
    }
	
	public static function ImportCategory ($document, $context) {
		$data = XliffReader::parseData($document->Contents);
		unset($data['store_id']);
		unset($data['creation_time']);
		unset($data['update_time']);	
		
		$storeId = Utilities::getStoreIdByLanguageCode($document->Language);
		$langCategory = Mage::getModel('catalog/category')->setStoreId($storeId)->load($data['entity_id']);
		$langData = $langCategory->getData();		
		$langData = array_merge($langData, $data);
		
		$langCategory->setData($langData)->save();
	}
}
