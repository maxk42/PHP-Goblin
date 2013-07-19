<?php

class Validate {
	public static function __invoke($value, $conditions) {
		$conditions = (array) $conditions;
		//return array_map(array('Validate', 'verify'), $conditions);
		$result = array();
		foreach($conditions as $condition)
			$result[] = self::verify($value, $condition);
		
		return $result;
	}
	
	public function verify($value, $condition) {
		// If $condition is an array, then the first element is the condition, and the remaining elements are parameters to that condition.
		// e.g.: array('minLength', 1)	// Make sure the string length of the value is at least 1.
		$condition = array('Validate', $condition);
		$parameters = $value;
		$value = array_shift($value);
		
		if(is_callable($condition))
			return call_user_func_array($condition, $parameters);
		else
			print_r($condition);
		
		return null;
	}
	
	public static function isInt($val) {
		return filter_var($val, FILTER_VALIDATE_INT);
	}
	
	public static function isFloat($val) {
		return filter_var($val, FILTER_VALIDATE_FLOAT);
	}
	
	public static function isBool($val) {
		return filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== null;
	}
	
	public static function isUrl($val) {
		return filter_var($val, FILTER_VALIDATE_URL);
	}
	
	public static function matchRegex($val, $regex) {
		return filter_var($val, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $regex)));
	}
	
	public static function gt($val, $amt) {
		return $val > $amt ? true : false;
	}
	
	public static function gte($val, $amt) {
		return $val >= $amt ? true : false;
	}
	
	public static function lt($val, $amt) {
		return $val < $amt ? true : false;
	}
	
	public static function lte($val, $amt) {
		return $val <= $amt ? true : false;
	}
	
	public static function eq($val, $amt) {
		return $val == $amt ? true : false;
	}
	
	// Check if two terms are exactly equal, including same type.
	public static function xeq($val, $amt) {
		return $val === $amt ? true : false;
	}
	
	public static function between($val, $lBound, $uBound, $inclusive = true) {
		if($inclusive)
			return ($val >= $lBound && $val <= $uBound);
		return ($val > $lBound && $val < $uBound);
	}
	
	public static function isEmail($val) {
		return filter_var($val, FILTER_VALIDATE_EMAIL);
	}
	
	public static function lenIs($val, $len) {
		return strlen((string) $val) == $len;
	}
	
	public static function minLen($val, $len) {
		return strlen((string) $val) >= $len;
	}
	
	public static function maxLen($val, $len) {
		return strlen((string) $val) <= $len;
	}
	
	public static function lenBetween($val, $lBound, $uBound) {
		$valLen = strlen((string) $val);
		return ($valLen >= $lBound && $valLen <= $uBound);
	}
	
	public static function isDigits($val) {
		return ctype_digit($val);
	}
	
	public static function is_scalar($val) {
		return is_scalar($val);
	}
	
	public static function is_array($val) {
		return is_array($val);
	}
	
	public static function trim($val, $charList = null) {
		if($charList !== null)
			return trim($val, $charList);
		return trim($val);
	}
	
	public static function isAlnum($val) {
		return ctype_alnum($val);
	}
	
	public static function isAlpha($val) {
		return ctype_alpha($val);
	}
	
	public static function isCntrl($val) {
		return ctype_cntrl($val);
	}
	
	public static function isGraph($val) {
		return ctype_graph($val);
	}
	
	public static function isLower($val) {
		return ctype_lower($val);
	}
	
	public static function isUpper($val) {
		return ctype_upper($val);
	}
	
	public static function isPunct($val) {
		return ctype_punct($val);
	}
	
	public static function isPrint($val) {
		return ctype_print($val);
	}
	
	public static function isSpace($val) {
		return ctype_space($val);
	}
	
	public static function isXDigit($val) {
		return ctype_xdigit($val);
	}
	
	public static function isEmpty($val) {
		return empty($val);
	}
}

$test = new Validate();
var_dump($test('testing', array(array('matchRegex', '/es/'), 'isAlpha', 'isEmpty')));
