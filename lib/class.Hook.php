<?php

class Hook {
	public static $hooks = array();
	
	public static function register($action, $callback, $weight = 0) {
		if(!isset(self::$hooks[$action][$weight]))
			self::$hooks[$action][$weight] = array();
		self::$hooks[$action][$weight][] = $callback;
	}
	
	public static function invoke($action) {
		// Get any additional arguments passed to this method, and discard the $action,
		// which is already stored.
		$passedArgs = func_get_args();
		array_shift($passedArgs);
		
		// Nothing to do?  Return the arguments passed in without further processing.
		if(!isset(self::$hooks[$action]) || !count(self::$hooks[$action]))
			return $passedArgs;
		
		// Turn the values into references.  This will allow functions to modify
		// their parameters before their successors run.
		$args = array();
		foreach($passedArgs as $k => $v) {
			$args[] = &$passedArgs[$k];
		}
		
		ksort(self::$hooks[$action]);
		$callbacks = array();
		foreach(self::$hooks[$action] as $weight)
			$callbacks = array_merge($callbacks, $weight);
		
		foreach($callbacks as $callback)
			call_user_func_array($callback, $args);
		
		return $args;
	}
}


