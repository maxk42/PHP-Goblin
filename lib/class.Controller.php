<?php

// Version 0.2 - Added 'static' keyword.  Class may no longer be backward-compatible with PHP 4.
class Controller {
	// This function takes $targetObj and invokes method $input if and only if $input is specified in $validList.
	// If $validList is omitted, any $input will be accepted.
	static function Control(&$targetObj, $input = FALSE, $validList = FALSE) {
		// 
		// 
		// 
		// 
		// 
		// 
		// 

		if($input === FALSE)	// default to $_GET['a'] for our input if none is specified
			$input = $_GET['a'];

		if($validList === FALSE)
			return $targetObj->$input();

		if(!in_array($input, $validList))
			return FALSE;

		return $targetObj->$input();
	}
}

