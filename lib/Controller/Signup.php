<?php
namespace Mvc0623\Controller;

/*
 * 新規登録メソッド
 * 今回は使わない
 */
class Signup extends \Mvc0623\Controller
{
	public function run()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST')	{
			if( isset($_POST['signup']) )	{
				$this->_signup();
			}
		}
	}

	private function _signup()
	{
		try	{
			$val = $this->_validate();

			if ($this->hasError()) {
				return;
			}

			$Thread = new \Mvc0623\Model\User();
			$Thread->create($val['username'], $val['email'], $val['password']);
		}
		catch (\Exception $e) {
			$this->setError('email', $e->getMessage());
			return;
		}
		header('Location:'.SITE_URL);
		exit;
	}

	private function _validate()
	{
		$this->validateToken();

		$username = filter_input(INPUT_POST, 'username');
		if ($username === '') {
			$this->setError('username', '入力してください');
		}
		else {
			$this->setValue('username', $username);
		}

		$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

		if ($email) {
			$this->setValue('email', $email);
		}
		else {
			$this->setError('email', '正しいメールアドレスを入力してください');
		}

		$password = filter_input(INPUT_POST, 'password');

		if (!preg_match('/\A[a-zA-z0-9]{4,8}\z/', $_POST['password'])) {
			$this->setError('password', '半角英数4-8文字');
		}
		else {
			$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
		}
		return ['username'=>$username, 'email'=>$email, 'password'=>$password];
	}
}
?>
