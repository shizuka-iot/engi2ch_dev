<?php
// Mvc0623\Controller\Thread
// 		 lib/Controller\Thread.php

spl_autoload_register(function($class){
	$prefix = 'Mvc0623\\';
	if( strpos($class, $prefix) === 0 )
	{
		$className = substr($class, strlen($prefix));
		// var_dump($className);
		$classFilePath =
		 	__DIR__.'/../lib/'.str_replace('\\','/', $className).'.php';
		// var_dump($classFilePath);
		if( file_exists($classFilePath) )
		{
			require $classFilePath;
		}
	}
});
?>
