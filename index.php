<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once('lib/config.php');
require_once('lib/class.Router.php');

Hook::register('return routes', 
	function(&$routes) {
		$routes[] = array(
			//'method' => 'GET',
			'route' => '/store/',
			'callback' => function($site) {
				$site->view->showPage('index.html');
			}
		);
	}
);
$site = new Site();

