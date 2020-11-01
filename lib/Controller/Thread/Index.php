<?php
namespace Mvc0623\Controller\Thread;

class Index extends \Mvc0623\Controller\Thread
{
	public function run()
	{
		$this->_redirect();

		if( $_SERVER['REQUEST_METHOD'] === 'POST' )
		{
			if( isset($_POST['login']) )
			{
				$this->login();
			}
			if( isset($_POST['create_thread']) )
			{
				$this->createThread();
			}
			if( isset($_POST['reply']) )
			{
				$this->reply();
			}
		}
	}

	private function _redirect()
	{
		// セッション変数のsuccessが存在していれば括弧内の処理を行う。
		// 多分postがあったらsuccessに値を入れるのだと思うが、
		// どういう処理をしたのか覚えていない。
		if( isset($_SESSION['success']) )
		{
			// 中身が真なら中身を空にしてからリダイレクト
			if( $_SESSION['success'] === true )
			{
				unset($_SESSION['success']);
				header('Location:'.SITE_URL.'#');
				exit;
			}
		}
	}

}
?>
