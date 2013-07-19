<?php

class View {
	public $searchDirs = array('./templates/', './');
	
	public function __construct($searchDirs = null) {
		if(!is_null($searchDirs)) {
			$this->searchDirs = (array) $searchDirs;
		}
		
		if(class_exists('Hook')) {
			$this->searchDirs = Hook::invoke('get template directories', $this->searchDirs);
			$this->searchDirs = $this->searchDirs[0];
		}
	}
	
	static public function parseString($string, array $parseTokens = array()) {
		foreach($parseTokens as $token => $replacement)
			$string = str_replace($token, $replacement, $string);
		
		return $string;
	}
	
	public function parseTemplate($templateFile, array $parseTokens = array(), array $searchDirs = array()) {
		if(class_exists('Hook')) {
			$template = self::viewPath($templateFile, $searchDirs);
			if($template === false)
				$template = $templateFile;
			$templateFile = $template;
		}
		
		if(($page = file_get_contents($templateFile)) === false)
			return false;						// couldn't open file; return false
		
		return View::parseString($page, $parseTokens);
	}
	
	public function showPage($templateFile, array $parseTokens = array(), array $searchDirs = array()) {
		echo $this->parseTemplate($templateFile, $parseTokens, $searchDirs);
	}
	
	public function viewPath($file, array $searchDirs = array()) {
		$searchDirs = array_merge($searchDirs, $this->searchDirs);
		foreach($searchDirs as $dir) {
			if(is_file($dir . $file))
				return $dir . $file;
		}
		return false;
	}
	
	// Pass in a single directory to add, or an array of directories.
	public function addSearchDir($dirs) {
		return $this->searchDirs = array_merge($this->searchDirs, (array) $dirs);
	}
	
	public function clearSearchDirs() {
		return $this->searchDirs = array();
	}
}

