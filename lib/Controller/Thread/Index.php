<?php
namespace Mvc0623\Controller\Thread;

class Index extends \Mvc0623\Controller\Thread
{
	public function run()
	{
		$this->_redirect();

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if( isset($_POST['create_thread']) ) {
				$this->createThread();
			}
			if( isset($_POST['reply']) ) {
				$this->reply();
			}
		}
	}

	/*
	 * セッション変数のsuccessキーがtrueだったらリダイレクト処理
	 */
	private function _redirect()
	{
		if ( isset($_SESSION['success']) ) {
			// 中身が真なら中身を空にしてからリダイレクト
			if( $_SESSION['success'] === true ) {
				unset($_SESSION['success']);
				header('Location:'.SITE_URL.'#');
				exit;
			}
		}
	}
}
?>
