<?php 

function __autoload($class_name)
{
	# List all the class directions in the array
	$array_pathes = array(
		'/models/',
		'/components/',
	);

	foreach ($array_pathes as $path) {
		$path = ROOT . $path . $class_name . '.php';

		if (is_file($path)) {
			require_once $path;
		}
	}
}
