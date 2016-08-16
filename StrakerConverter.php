<?php

if(!defined('BASEPATH')) exit('No direct script access allowed!');
require_once(APPPATH . 'libraries/languageFileConverter/LanguageFileConversion_lib.php');

/**
 * Conversion class for the straker api
 * @author david.williams
 * @param  String $xsl path to .xsl file.
 * @param  String $xml path to .xml file.
 */
class StrakerConverter extends LanguageConverter implements translationConverter
{
	
	protected $xsl;
	protected $xml;
	protected $processor;

	public function __construct($params = null)
	{
		$this->xsl = new DOMDocument;
		$this->xml = new DOMDocument;

		if($params['xsl'] === null || $params['xml'] === null)
			return;
		
		$this->xsl->load($params['xsl']);
		$this->xml->load($params['xml']);
		$this->processor = new XSLTProcessor;
		$this->processor->importStyleSheet($this->xsl);
	}

	/**
	 * Converts an XML file to CI language file.
	 * @author  david.williams 	 
	 * @return  null
	 */
	public function XMLToLang()
	{
		$clientFileName = $this->getCurrentFileName();
		$clientSlug = str_replace("_lang", "", $clientFileName);
		$langDir = $this->getCurrentFileDir();
		$langSlug = $this->getLangSlug($langDir);
		$filePath = $langDir . '/';
		$fileName = $filePath . '/' . $clientFileName;
		$langFile = fopen($fileName . ".php", 'w');
		
		fwrite($langFile, "<?php\nif(!defined('BASEPATH')) exit('No direct script access allowed!');");

		$content = $this->processor->transformToXML($this->xml);
		
		fwrite($langFile, $content);
		
		if($clientSlug !== 'default')
			fwrite($langFile, "\nrequire_once 'default_lang.php';");
		
		if($langSlug !== 'english')
			fwrite($langFile, "\nrequire_once APPPATH .'language/english/default_lang.php';");
		
		fclose($langFile);

	}
	
	/**
	 * Static method to convert CI language file to Straker XML.
	 * @author  david.williams
	 * @param  String $langFile File path to language file.
	 * @return null           
	 */
	
	public static function langToXML($langFile)
	{
		if(!file_exists($langFile))
			throw new Exception('Language file does not exist.');
		
		define('BASEPATH', true);
		require_once $langFile;
		
		$instance = new self();
		$clientSlug = $instance->getClientSlug($langFile);
		$fileName = $clientSlug . "_lang";
		
		$XMLFileName = pathinfo($langFile)['dirname'] . '\\' . $fileName . '.xml';
		$XMLFile = fopen($XMLFileName, 'w');

		fwrite($XMLFile,"<?xml version='1.0' encoding='utf-8'?>\n<root>\n");
		fclose($XMLFile);

		foreach($lang as $key => $val){
			$appendedContent = "\t<data name=\"" . $key . "\">\n\t\t<value>" . $val . "</value>\n\t</data>\n";
			file_put_contents($XMLFileName, $appendedContent, FILE_APPEND | LOCK_EX);
		}

		file_put_contents($XMLFileName,"</root>\n", FILE_APPEND | LOCK_EX);
	}
}
