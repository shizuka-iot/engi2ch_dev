<?php
namespace Mvc0623;

class Controller
{
	private $_values;
	private $_errors;

	/*
	 * コンストラクタ
	 */
	public function __construct()
	{
		$this->_createToken();

		// stdClass()は連想配列のようにして使える
		$this->_values = new \stdClass();
		$this->_errors = new \stdClass();
	}


	/*
	 * トークン生成メソッド
	 */
	private function _createToken()
	{
		if( !isset($_SESSION['token']) )
		{
			$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
		}
	}


	/*
	 * トークン検証メソッド
	 */
	protected function validateToken()
	{
		if( !isset($_SESSION['token']) || !isset($_POST['token']) ||
			$_SESSION['token'] !== $_POST['token'] )
		{
			echo 'セッションの有効期限が切れました。';
			exit;
		}
	}


	/*
	 * セッター
	 */
	protected function setValue($key, $value)
	{
		$this->_values->$key = $value;
	}
	protected function setError($key, $error)
	{
		$this->_errors->$key = $error;
	}
	/*
	 * ゲッター
	 */
	public function getValue($key)
	{
		return isset($this->_values->$key) ? $this->_values->$key : '';
	}
	public function getError($key)
	{
		return isset($this->_errors->$key) ? $this->_errors->$key : '';
	}

	/*
	 * エラーの有無を判定するメソッド
	 * 有無を判定するだけで実際にどういう処理をするかは別の部分で行う
	 */
	protected function hasError()
	{
		return !empty(get_object_vars($this->_errors));
	}


	/*
	 * エラー表示メソッド
	 * this->_errorsオブジェクトのキーを指定すると
	 * そのプロパティを取り出してhtml上に出力する
	 *
	 * @param string $key // this->_errorsのキー
	 */
	public function showError($key)
	{
		$html = '<p class="err">'.$this->getError($key).'</p>';
		echo $html;
	}


	/*
	 * ログイン判定メソッド
	 * 今回は使わない
	 */
	protected function isLoggedIn()
	{
		return isset($_SESSION['me']) && !empty($_SESSION['me']);
	}


	/*
	 * セッションに入ってるログイン情報を返すメソッド
	 * ログイン状態を判定し
	 * コントローラーからセッションにログイン情報を入れるので
	 * それを取り出すのだが今回は使わない
	 */
	public function me()
	{
		return $this->isLoggedIn() ? $_SESSION['me'] : null;
	}


	/*
	 * スレッドに投稿された画像のurlを取り出すメソッド
	 * サムネイルフラグを確認してサムネが原寸かを返す
	 *
	 * @param string $fileName
	 * @param bool $thumbnail_flag
	 */
	public function getImgUrl($fileName, $thumbnail_flag)
	{
		if ($thumbnail_flag) {
			return basename(THUMBS_DIR).'/'.$fileName;
		}
		else {
			return basename(IMGS_DIR).'/'.$fileName;
		}
	}
}
?>
