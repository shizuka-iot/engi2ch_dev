<?php
namespace Mvc0623;

class Controller
{
	private $_values;
	private $_errors;

	public function __construct()
	{
		$this->_createToken();

		$this->_values = new \stdClass();
		$this->_errors = new \stdClass();
	}

	/*************************************************************
								トークン生成とバリデート
	*************************************************************/
	private function _createToken()
	{
		if( !isset($_SESSION['token']) )
		{
			$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
		}
	}
	protected function validateToken()
	{
		if( !isset($_SESSION['token']) || !isset($_POST['token']) ||
			$_SESSION['token'] !== $_POST['token'] )
		{
			echo 'セッションの有効期限が切れました。';
			exit;
		}
	}



	/*************************************************************
											セッターとゲッター
	*************************************************************/
	protected function setValue($key, $value)
	{
		$this->_values->$key = $value;
	}
	protected function setError($key, $error)
	{
		$this->_errors->$key = $error;
	}
	public function getValue($key)
	{
		return isset($this->_values->$key) ? $this->_values->$key : '';
	}
	public function getError($key)
	{
		return isset($this->_errors->$key) ? $this->_errors->$key : '';
	}
	protected function hasError()
	{
		return !empty(get_object_vars($this->_errors));
	}
	/*************************************************************
											エラーを表示
	*************************************************************/
	public function showError($key)
	{
		$html = '<p class="err">'.$this->getError($key).'</p>';
		echo $html;
	}

	/*************************************************************
											ログイン関連
	*************************************************************/
	protected function isLoggedIn()
	{
		return isset($_SESSION['me']) && !empty($_SESSION['me']);
	}
	public function me()
	{
		return $this->isLoggedIn() ? $_SESSION['me'] : null;
	}

	public function getImgUrl($fileName, $thumbnail_flag)
	{
		if( $thumbnail_flag )
		{
			return basename(THUMBS_DIR).'/'.$fileName;
		}
		else
		{
			return basename(IMGS_DIR).'/'.$fileName;
		}
	}
}
?>
