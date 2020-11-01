<?php
namespace Mvc0623;

class Model
{
	protected $pdo;

	public function __construct()
	{
		try
		{
			$this->pdo = new \PDO(DSN, DB_USER, DB_PASS);
			$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}
		catch(\PDOException $e)
		{
			echo $e->getMessage();
			exit;
		}
	}
}
?>
