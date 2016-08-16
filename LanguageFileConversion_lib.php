<?php

interface translationConverter
{
	public function XMLToLang();
	public static function langToXML($langFile);
}


class LanguageConverter
{

	/**
	 * Get language slug from file name.
	 * @author david.williams
	 * @param  String $path Path to xml or language file.
	 * @return String       Language slug.
	 */
	protected function getLangSlug($path)
	{
		$langSlug = str_replace("/", "\\", $path);
		$langSlug = explode("\\", $langSlug);
		return end($langSlug);
	}

	/**
	 * Get client custom slug from path.
	 * @author  david.williams
	 * @param  String $path Path to xml or language file.
	 * @return String       Client custom slug.
	 */
	protected function getClientSlug($path)
	{
		$langSlug = explode("_", pathinfo($path)['filename']);
		return $langSlug[0];
	}

	/**
	 * Get full file name from xml document.
	 * @author david.williams
	 * @return String name of file without extention.
	 */
	protected function getCurrentFileName(){
		$fileURI = pathinfo($this->xml->documentURI);
		return $fileURI['filename'];
	}

	/**
	 * Get directory of current xml.
	 * @author david.williams
	 * @return String directory of currently open xml file.
	 */
	protected function getCurrentFileDir(){
		$fileURI = pathinfo(str_replace("file:/", "", $this->xml->documentURI) );
		return $fileURI['dirname'];
	}

}


