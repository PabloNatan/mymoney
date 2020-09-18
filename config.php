<?php
	spl_autoload_register(function($className)
	{
		$path = "php-classes" . DIRECTORY_SEPARATOR . "$className.php";

		if(file_exists($path) === true) require_once($path);
	});

?>