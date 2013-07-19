<?php
/*

Concept:

Pass in array:
array(
	'/url' => function() {
		// callback
	},
	'/some/other/url' => array(
		'GET' => function() {
			// callback for GET method
		},
		'POST' => function() {
			// callback for POST method
		}
	),
	'an?.+other/(url|path)' => function($urlOrPath) {
		// parenthesized subexpressions will be passed to callback as subsequent parameters
	},
	'/yet/another/{path}' => array(
		'_type' => 'token',
		'_callback' => function($path) {
			// With a 'type' parameter set, a different type of match can be specified (e.g. 'token', 'regex', 'literal', etc.)
		}
	)
	
)

url => callback
|
url => array(
	requestMethod => callback,
	requestMethod => callback
)
|
url => array(
	requestMethod => callback,
	'_type' => token,
	'_weight' => int
)
|
type => array(
	'url' => string,
	'method' => requestMethod,
	'callback' => callback,
	'weight' => int
)

*/

class Router {
	public static $routes = array();
	
	static function add($route, $callback, $weight = 0) {
		Router::$routes[$weight]['#' . $route . '#'] = $callback;
	}
	
	static function build(array $routes) {
		Router::$routes = array();				// Initialize the array.
		
		foreach($routes as $weight => $route) {
			// If no weight was specified (e.g. the routes array was in the form: array('/route' => 'callback', '/route2' => 'callback2') )
			// then go ahead and normalize the input, setting the weight to 0.
			if(!is_array($route)) {
				$route = array($weight => $route);
				$weight = 0;
			}
			
			list($route, $callback) = array(key($route), current($route));
			Router::add($route, $callback, $weight);
		}
		
		return Router::$routes;
	}
	
	static function execute() {
		$args = func_get_args();				// Any additional arguments will be passed along to candidate routes
		ksort(Router::$routes, SORT_NUMERIC);
		$matches = array();
		array_walk_recursive(
			Router::$routes,
			function($callback, $route, $args) {
				if(preg_match($route, $_SERVER['REQUEST_URI'], $matches)) {
					array_shift($matches);
					return call_user_func_array($callback, array_merge($matches, $args));
				}
			},
			$args
		);
	}
}

// Usage:
// // Initialize our object.
// $routes = new Router(function() { echo "This is the default path.  Any path not matching another path will invoke this function."; });
// 
// // Specify our routes.
// $routes->GET('/', function() {
// 	echo "You accessed the root directory.";
// });
// $routes->POST('/path/{var1}/{var2}', function($var1, $var2) {
//	echo "You specified get data: ", $var1, " and ", $var2, "<br>";
// 	echo "You specified post data: ", print_r($_POST, true), "<br>";
// });
// 
// // Invoke the router, executing the function associated with the current route, if any.
// $routes();	// Execute the route associated with the current path.  Throws UnexpectedValueException('Path not found.') if no route matches.
// 
// If no route is found, the router will first execute a Hook called 'path not found', then execute the callback stored in Router::$notFound,
// which defaults to the exit() command.  If execution has not been halted at that point, it will throw an exception of type
// UnexpectedValueException with the message 'Path not found.'
// 
/*
class Router {
	public $routes;
	public $type = 'regex';
	
	private static $defaultSchema = array(
		'_type' => 'regex',
		'_callback' => array('View', 'showPage'),
		'_method' => 'GET',
		'_weight' => 0
	);
	
	function __construct($routes = null, Callback $defaultCallback = null, $execute = true) {
		if(is_array($routes))
			$this->routes = $routes;
		
		
	}
	
	function execute() {
		array_map(array($this, 'normalizeRouteInfo'), array_keys($this->routes), $this->routes);
		foreach($routes as $route => $info) {
			
		}
	}
	
	function normalizeRouteInfo($key, $info) {
		$normalizedInfo = self::$defaultSchema;
		
		$type = $this->type;
		if(!self::isRoute($key))
			$type = $key;
		else
			$info['
		
		$normalizedInfo[$type]
	}
	
	function isRoute($var) {
		return (is_string($var) && $var[0] == '/');
	}
	
	
}

/*
class Router {
	public static $uris = array();						// Uniform Resource Indicator array
	public static $notFound = 'exit';					// Callback in the event no route is found
	
	// @method __construct([...])
	// @parameter $method
	// @parameter $uris
	function __construct($defaultCallback = 'exit', $preserveUris = false) {
		$this->defaultCallback = $defaultCallback;
		if(!$preserveUris)
			$this->uris = array();
	}
	
	// See http://www.php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.methods
	function add($method, $route, $callback) {
		$this->uris[strtoupper($method)][$route] = $callback;
	}
	
	function execute() {
		if(!isset($this->uris[$_SERVER['REQUEST_METHOD']]))
			throw new UnexpectedValueException('Request method not found.');
			
		$args = func_get_args();			// Get any arguments passed to this function, to be passed to the appropriate route.
		foreach($this->uris[$_SERVER['REQUEST_METHOD']] as $uri => $callback) {
			$vars = array();
			if(preg_match_all('#\\{[a-zA-Z_][a-zA-Z_0-9]+\\}#', $uri, $vars)) {
				$vars = $vars[0];
				foreach($vars as $var)
					$uri = str_replace($var, '([^/]+)', $uri);
				$values = array();
				if(preg_match('#^' . $uri . '$#', $_SERVER['REQUEST_URI'])) {
					preg_match('#' . $uri . '#', $_SERVER['REQUEST_URI'], $values);
					array_shift($values);
					return call_user_func_array($callback, array_merge($args, array_combine($vars, $values)));
				}
			}
			// Path is a string literal match.
			if(!strcmp($uri, $_SERVER['REQUEST_URI']))
				return call_user_func_array($callback, $args);
		}
		
		// No route was found.  If a hook exists, execute it.  Otherwise, if $this->notFound is a
		// callback / closure, execute it.  Finally, throw an UnexpectedValueException if no
		// other function has halted execution yet.
		if(class_exists('Hook'))
			Hook::invoke('path not found');
		
		if(is_callable(self::$notFound))
			call_user_func(self::$notFound);
		
		throw new UnexpectedValueException('Path not found.');
	}
}
*/

