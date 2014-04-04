No documentation yet -- for now, to create a new site, define the dataabase constants in config.php, create an index file called index.html then build a site like this:


	<?php

	include_once 'lib/config.php';

	Hook::register('return routes', function($routes) {
		'method' => 'GET',
		'route' => '/',
		'callback' => function($site) {
			$site->view->showPage('index.html');
		}
	));
	$site = new Site();


