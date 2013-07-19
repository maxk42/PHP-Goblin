<?php

/*

type => predefined type of validation to perform, including built-in sanitization and 
validate => array of validation filters to execute
sanitize => array of sanitization filters to execute
	ex.: array('trim', 'preg_match' => array(

$name = new Validatable($_POST['name'])->setRules(array(
	// 'validate' rules return boolean values.  If any return false, the input does not pass validation.
	'validate' => array(
		'len_gte' => 4,
		'len_lte' => 12,
		'callbacks' => array(
			'ctype_alnum',
			function($input) {
				return ctype_alpha(substr($input, 0, 1));
			},
			'preg_match' => array('/^admin/', array($name, 'getCleanInput')),
		),
	),
	
	// 'sanitize' rules are filters to be applies to
	'sanitize' => array(
		'trim',
		'filter_var' => 
	)
));

*/
class Validator {
	public function __construct($validatables = null) {
		$args = func_get_args();
		if($validatables === null)
			return;
		$validatables = (array) $validatables;
		foreach($validatables as $validatable)
			$this->validate($validatable);
		
		return $this;
	}
	
	public function validate(Validatable $input) {
		if(isset($input['type'])) {
			//self::validate$input['type']($input);
		}
	}
}

class Validatable implements ArrayAccess {
	private $container = array();
	public $originalInput = null;
	public $input = null;
	
	public function __construct() {
		$sanitize = array();
		$validate = array();
		
		$args = func_get_args();
		if(count($args)) {
			if(count($args) == 1) {
				$args = $args[0];
				if(isset($args['sanitize']))
					$sanitize = $args['sanitize'];
				if(isset($args['validate']))
					$validate = $args['validate'];
			}
			if(count($args) == 2) {
				$sanitize = $args[0];
				$validate = $args[1];
			}
		}
		$this->container = array(
			'sanitize' => $sanitize,
			'validate' => $validate
		);
	}
	
	public function &getInput() {
		return $this->originalInput;
	}
	
	public function &getCleanInput() {
		return $this->input;
	}
	
	public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			$this->container[] = $value;
		} else {
			$this->container[$offset] = $value;
		}
	}
	
	public function offsetExists($offset) {
		return isset($this->container[$offset]);
	}
	
	public function offsetUnset($offset) {
		unset($this->container[$offset]);
	}
	
	public function offsetGet($offset) {
		return isset($this->container[$offset]) ? $this->container[$offset] : null;
	}
}

class Test {
	public $input;
	
	public function __construct($input) {
		$this->input = $input;
		$this->callbacks = $callbacks;
	}	// constructors cannot return anything during object instantiation -- the object itself will be returned regardless of any return values specified.  Note that this prevents chaining.
	
	public function validate($callbacks) {
		foreach($callbacks as $cb => $args) {
			echo $this->input = call_user_func_array($cb, $args), "\n";
		}
	}
	
	public function &getInput() {
		return $this->input;
	}
}

//$test = new Test('xyzzy')->validate(array('filter_var' => array($test, 'getInput')));
$test = new Test('xyzzy');

