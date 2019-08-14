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
 
class XliffWriter extends XMLWriter
{

    /**
     * Constructor.
     * @param string $prm_rootElementName A root element's name of a current xml document
     * @param string $prm_xsltFilePath Path of a XSLT file.
     * @access public
     * @param null
     */
      var $_phrase_id=1;
    public function __construct(){
        $this->openMemory();
        $this->setIndent(true);
        $this->setIndentString(' ');
        $this->startDocument('1.0', 'UTF-8');
         
        if($prm_xsltFilePath){
            $this->writePi('xml-stylesheet', 'type="text/xsl" href="'.$prm_xsltFilePath.'"');
        }
        $this->startElement('xliff');
        $this->writeAttribute('version', '1.0');
        $this->startElement('file');
        $this->writeAttribute('original', 'global');
        $this->writeAttribute('source-language', 'es');
        $this->writeAttribute('datatype', 'plaintext');
        $this->writeAttribute('date', date('c'));
        $this->startElement('body');
    }
    public function addPhrase($source, $target){
        $this->startElement('trans-unit');
      $this->writeAttribute('id', $this->_phrase_id++);
        $this->startElement('source');
        $this->text($source);
      $this->endElement();
        $this->startElement('target');
      $this->text($target);
      $this->endElement();
      $this->endElement();
    }
	
	public function addTransUnit($fieldName, $content){
		$this->startElement('trans-unit');
      $this->writeAttribute('id', $this->_phrase_id++);
	  $this->writeAttribute('rel', $fieldName);
        $this->startElement('source');
        $this->text($content);
      $this->endElement();
        $this->startElement('target');
      $this->text($content);
      $this->endElement();
      $this->endElement();
	}
	
    public function getDocument(){
        $this->endElement();
        $this->endElement();
        $this->endElement();
        $this->endDocument();
        return $this->outputMemory();
    }
    public function output(){
        header('Content-type: text/xml');
        echo $this->getDocument();
    }
}
?>