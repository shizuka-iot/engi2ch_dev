<?php
function h($s)
{
	return htmlspecialchars($s, ENT_QUOTES, 'utf-8');
}


function checkInput($var)
{
	if (is_array($var)) {
		// array_map関数は、対象の配列の全要素に対して、
		// 指定した関数を適用させる際に使用します。
		// 第一引数のコールバック関数名は文字列を指定する。
		// 第二引数はコールバック関数を適用する配列を指定。
		return array_map('checkInput', $var);
	}
	else {
		// NULLバイト攻撃対策
		// 正規表現に'\0'が含まれていたら、
		if (preg_match('/\0/', $var)) {
			// 引数内の文字列を出力して終了。
			die('不正な入力です');
		}
		// 文字エンコードのチェック
		if ( !mb_check_encoding($var, 'UTF-8') )	{
			die('不正な入力です');
		}
		// 改行・タブ以外の制御文字のチェック
		if (preg_match('/\A[\r\n\t[:^cntrl:]]*\z/u', $var) === 0 ) {
			die('不正な入力です');
		}
		return $var;
	}
}
?>
