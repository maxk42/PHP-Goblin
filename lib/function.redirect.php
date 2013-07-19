<?php

function redirect($dest) {
	header('Location: ' . $dest);
	exit();
}

