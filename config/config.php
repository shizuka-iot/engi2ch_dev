<?php
ini_set('display_errors', 1);

if( !function_exists('imagecreatetruecolor') )
{
	echo 'GDがインストールされていません';
	exit;
}

require __DIR__.'/../lib/functions.php';
require __DIR__.'/common.php';
require __DIR__.'/autoload.php';

session_start();

// postしたあと戻るボタンを押すとドキュメントの有効期限切れで
// ページが見えなくなるのを防ぐ記述。
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');

?>
