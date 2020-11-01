<?php
require __DIR__.'/../config/config.php';

$Vote = new \Mvc0623\Controller\VoteController();

// vote.jsからajaxでPOSTすることが出来る。
if($_SERVER['REQUEST_METHOD'] === 'POST')
{
	try
	{
		$res = $Vote->post();
		header('Content-Type: application/json');
		echo json_encode($res);
		exit;
	}
	catch(Exception $e)
	{
		header($_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error', true, 500);
		echo $e->getMessage();
		exit;
	}
}
?>
