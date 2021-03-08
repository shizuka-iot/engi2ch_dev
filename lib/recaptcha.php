<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$recaptcha_response = $_POST['recaptcha_response'];
	$recaptcha_secret = RECAPTCHA_SECRET;

	$recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify?secret=';
	$recaptcha = file_get_contents( 
		$recaptcha_url.$recaptcha_secret. '&response='.$recaptcha_response
	);
	$recaptcha = json_decode($recaptcha);

	print_r('$recaptcha->score : '.var_export($recaptcha->score,true));
	if ($recaptcha->score >= 0.5) {
		// reCAPTCHA合格
		// そのまま送信処理へ
	} 
	else {
		// reCAPTCH不合格。ボットの可能性があるので、送信しない
		echo '送信できませんでした';
		exit;
	}
}
