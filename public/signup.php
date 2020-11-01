<?php
require __DIR__.'/../config/config.php';

$SignupCtr = new \Mvc0623\Controller\Signup();
$SignupCtr->run();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?=h(SITE_TITLE)?></title>
	<!-- font awesome cssファイルなのでヘッド内で読み込む-->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
	<link rel="stylesheet" href="styles.css">
</head>
<body>

<header class="center">
	<div class="container row between">

		<div class="header_logo_wrap">
			<a href="<?=h(SITE_URL)?>">
				<h1 class="color_lb"><?=h(SITE_TITLE)?></h1>
				<p class="color_lb fs_13"><?=h(SITE_SUBTITLE)?></p>
			</a>
		</div>

		<div class="header_right">
		</div>

	</div>
</header>

<div class="center">
	<div class="container">
		<div class="login_form_wrap">
			<form method="post" action="">

				<input type="hidden" name="token"
				 value="<?=h($_SESSION['token'])?>">

				<?php $SignupCtr->showError('username')?>
				<input type="text" name="username" placeholder="ユーザー名"
				 value="<?=h($SignupCtr->getValue('username'))?>">

				<?php $SignupCtr->showError('email')?>
				<input type="text" name="email" placeholder="メールアドレス"
				 value="<?=h($SignupCtr->getValue('email'))?>">

				<?php $SignupCtr->showError('password')?>
				<input type="password" name="password" placeholder="パスワード">

				<button type="submit" name="signup">新規登録</button>
			</form>
		</div>
	</div>
</div>

<footer class="center">
	<div class="container">
	</div>
</footer>
	
<!-- jqueryのCDN jsなのでbodyの閉じタグ直前に読み込む -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</body>
</html>
