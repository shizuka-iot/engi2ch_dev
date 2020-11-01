<?php
namespace Mvc0623\Model;

class User extends \Mvc0623\Model
{
	public function create($username, $email, $password)
	{
		$sql = 'insert into user
			(username, email, password, created_at, updated_at) values
	 	  (:username, :email, :password, now(), now())';
		$stmt = $this->pdo->prepare($sql);
		$res = $stmt->execute(
			[':username'=>$username, ':email'=>$email, ':password'=>$password]);
		if( $res === false )
		{
			throw new \Exception('既に登録のあるメールアドレスです');
		}
	}

	public function login($val)
	{
		$sql = 'select * from user where email = ?';
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$val['email']]);
		$stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
		$user = $stmt->fetch();

		if( empty($user) )
		{
			throw new \Exception('メールアドレス又はパスワードが間違っています');
		}
		if( !password_verify($val['password'], $user->password) )
		{
			throw new \Exception('メールアドレス又はパスワードが間違っています');
		}
		return $user;
	}
}
?>
