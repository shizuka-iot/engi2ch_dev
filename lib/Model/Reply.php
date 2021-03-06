<?php
namespace Mvc0623\Model;

class Reply extends \Mvc0623\Model
{
	/*
	 * 返信をDBにINSERT
	 *
	 * @param array $arr
	 */
	public function reply($arr)
	{
		$sql = 'insert into reply
		 	(thread_no, auther, body, created_at, updated_at) 
			values
		 	(:thread_no, :auther, :body, now(), now() )';
		$stmt = $this->pdo->prepare($sql);
		$res = $stmt->execute([
			':thread_no'=>$arr['thread_no'],
			':auther'=>$arr['auther'],
			':body'=>$arr['body']
		]);

		if( $res === false ) {
			throw new \Exception('DBエラー');
		}
	}


	/*
	 * 指定スレッドの返信を全てSELECT
	 *
	 * @param int $thread_no
	 */
	public function findReplies($thread_no)
	{
		$sql = 'select * from reply where thread_no = ? and delete_flag = 0';
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$thread_no]);
		$stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
		return $stmt->fetchAll();
	}


	/*
	 * good/badの値をUPDATE
	 */
	public function vote()
	{
		switch ($_POST['mode']) {
			// goodボタンが押された場合の処理
			case 'good':
				
				// トランザクション開始
				$this->pdo->beginTransaction();

					// クッキーにgoodボタンを押した痕跡があればキャンセル処理
					if( isset($_COOKIE['good_'.$_POST['id']]) &&
										$_COOKIE['good_'.$_POST['id']] === '1') {
						// goodを下げる（キャンセル）
						$this->_down_good_count($_POST['id']);
						// クッキーも削除してgoodをなかったことに。
						setcookie('good_'.$_POST['id'], '', time()-60*60*24);
					}
					else {
						// goodカウントを上げる。
						$this->_up_good_count($_POST['id']);

						// goodが押されたことをクッキーに記憶させる。
						setcookie('good_'.$_POST['id'], true, time()+60*60*24);
					}
					// 既にbadも押されていたらbadカウントを下げる。
					if( isset($_COOKIE['bad_'.$_POST['id']]) &&
										$_COOKIE['bad_'.$_POST['id']] === '1') {
						// badを下げる（キャンセル）
						$this->_down_bad_count($_POST['id']);

						// クッキーも削除してbadをなかったことに。
						setcookie('bad_'.$_POST['id'], '', time()-60*60*24);
					}

				// トランザクション終了
				$this->pdo->commit();

				// good/bad両方の値を取り出し。
				$vote['good'] = $this->_get_good_count($_POST['id']);
				$vote['bad'] = $this->_get_bad_count($_POST['id']);
				return $vote;

			// badボタンが押された場合の処理
			case 'bad':

				// トランザクション開始
				$this->pdo->beginTransaction();

					// クッキーにbadボタンを押した痕跡があればキャンセル処理
					if (isset($_COOKIE['bad_'.$_POST['id']]) &&
										$_COOKIE['bad_'.$_POST['id']] === '1') {
						// badカウントをもとに戻す
						$this->_down_bad_count($_POST['id']);
						// クッキーも削除してbadボタンが押された痕跡を消す
						setcookie('bad_'.$_POST['id'], '', time()-60*60*24);
					}
					// クッキーにbadボタンを押された痕跡がないときはbadカウントを増やす
					else {
						// badカウントを上げる。
						$this->_up_bad_count($_POST['id']);

						// badが押されたことをクッキーに記憶させる。
						setcookie('bad_'.$_POST['id'], true, time()+60*60*24);
					}
					// 既にgoodも押されていたらgoodカウントを下げる。
					if (isset($_COOKIE['good_'.$_POST['id']]) &&
										$_COOKIE['good_'.$_POST['id']] === '1') {
						$this->_down_good_count($_POST['id']);
						// goodボタンが押されたのをなかったことに。
						setcookie('good_'.$_POST['id'], '', time()-60*60*24);
					}

				// トランザクション終了
				$this->pdo->commit();

				// good/bad両方の値を取り出し。
				$vote['good'] = $this->_get_good_count($_POST['id']);
				$vote['bad'] = $this->_get_bad_count($_POST['id']);
				return $vote;
		}
	}

	/*
	 * 指定のリプライのgoodカウントをアップ
	 *
	 * @param int $reply_no
	 */
	private function _up_good_count($reply_no)
	{
		// 更新
		$sql = 'update reply set good = good + 1 where no = ?';
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$reply_no]);
	}


	/*
	 * 指定のリプライのgoodカウントを取得
	 *
	 * @param int $reply_no
	 */
	private function _get_good_count($reply_no)
	{
		// 更新した値を取り出し
		$sql = sprintf('select good from reply where no = %s', $reply_no);
		$stmt = $this->pdo->query($sql);
		return $stmt->fetchColumn();
	}


	/*
	 * 指定のリプライのbadカウントをアップ
	 *
	 * @param int $reply_no
	 */
	private function _up_bad_count($reply_no)
	{
		// 更新
		$sql = 'update reply set bad = bad + 1 where no = ?';
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$reply_no]);

	}


	/*
	 * 指定のリプライのbadカウントを取得
	 *
	 * @param int $reply_no
	 */
	private function _get_bad_count($reply_no)
	{
		// 更新した値を取り出し
		$sql = sprintf('select bad from reply where no = %s', $reply_no);
		$stmt = $this->pdo->query($sql);
		return $stmt->fetchColumn();
	}


	/*
	 * 指定のリプライのgoodカウントをダウン
	 *
	 * @param int $reply_no
	 */
	private function _down_good_count($reply_no)
	{
		// 更新
		$sql = 'update reply set good = good - 1 where no = ?';
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$reply_no]);
	}


	/*
	 * 指定のリプライのbadカウントをダウン
	 *
	 * @param int $reply_no
	 */
	private function _down_bad_count($reply_no)
	{
		// 更新
		$sql = 'update reply set bad = bad - 1 where no = ?';
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$reply_no]);
	}
}
?>
