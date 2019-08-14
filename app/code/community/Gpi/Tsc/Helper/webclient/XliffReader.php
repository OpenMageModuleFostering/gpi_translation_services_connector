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
 
class XliffReader {
	
	public static function parseData($xml){
		$dom = new DOMDocument();
		$dom->loadXML($xml);
		$transUnits = $dom->getElementsByTagName('trans-unit');
		
		$data = array();
		foreach($transUnits as $transUnit) {
			if ($transUnit->childNodes->length < 2 || !$transUnit->hasAttribute('rel')) {
				continue;
			}
			
			$key = $transUnit->getAttribute('rel');
			$target = $transUnit->getElementsByTagName('target');
			if ($target->length != 1) {
				continue;
			}						
			$data[$key] = $target->item(0)->nodeValue;
		}
		
		return $data;
    }
}
?>