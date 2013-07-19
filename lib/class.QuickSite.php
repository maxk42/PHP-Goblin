<?php

class Site {
	public $db;
	public $view;
	public $router;
	
	function __construct() {
		global $tokens;
		Hook::invoke('before constructor', $this);
		
		if(count($_POST)) {
			$_SESSION['post'] = $_POST;
			redirect($_SERVER['REQUEST_URI']);
		}
		
		if(defined('DB_SERVER') && defined('DB_USERNAME') && defined('DB_PASSWORD'))
			$this->db = new MySQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DBNAME);
		$this->view = new View();
		
		$tokens['<msg>'] = '';							// <msg> is used to display messages to the user
		
		if(isset($_SESSION['msg']))
			$this->viewParams['<msg>'] = $_SESSION['msg'];
		
		$this->router = new Router();
		$routes = array();
		$routes = Hook::invoke('return routes', $routes);			// Return $routes as an array in the form: array('method => 'GET', 'route' => '/path/to/{my}/route' , 'callback' => function() { myFunc(); })
		$routes = $routes[0];
		
		foreach($routes as $route)
			$this->router->add($route['method'], $route['route'], $route['callback']);
		
		Hook::invoke('before execution', $this);
		$this->router->execute($this);						// Execute the current path.
		Hook::invoke('afer execution', $this);
	}
}

