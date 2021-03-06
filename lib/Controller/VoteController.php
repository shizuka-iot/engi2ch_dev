<?php
namespace Mvc0623\Controller;

class VoteController extends \Mvc0623\Controller
{
	// DB接続はModelクラスで行う。
	// トークンの生成は親クラスで行っている。

	// vote.jsからjsonで送られてくる値は3種類
	// id: リプライ番号
	// mode: good/bad
	// token: トークン
	// postで送られてきたということはスーパーグローバルなので
	// $_POST[キー]で取り出せる

	/*
	 * good/badボタンが押されたときの処理
	 */
	public function post()
	{
		if( !isset($_POST['mode']) ) {
			throw new \Exception('mode not set');
		}

		return $this->_vote();
	}


	/*
	 * good/badをDBに書き込み
	 */
	private function _vote()
	{
		if( !isset($_POST['id']) && !isset($_POST['thread_no'])) {
			throw new \Exception('id no set');
		}

		if (isset($_POST['thread_no'])) {
			$threadModel = new \Mvc0623\Model\Thread();
			return $threadModel->vote($_POST);
		}
		else {
			$ReplyModel = new \Mvc0623\Model\Reply();
			return $ReplyModel->vote($_POST);
		}
	}
}
?>
