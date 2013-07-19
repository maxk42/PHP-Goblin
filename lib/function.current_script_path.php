<?php

function current_script_path() {
	return ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? "https://" : "http://" ) . $_SERVER['SERVER_NAME'] . (($_SERVER['SERVER_PORT'] != '80') ? ':' . $_SERVER['SERVER_PORT'] : '') . dirname($_SERVER['SCRIPT_NAME']);
}

?>