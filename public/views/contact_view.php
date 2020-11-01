<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?=h(SITE_TITLE)?></title>
	<link href="https://fonts.googleapis.com/css2?family=M+PLUS+1p&display=swap" rel="stylesheet">
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

	</div>
</header>

<div class="center main">
	<div class="container column">
		<h2>お問い合わせ</h2>
		<form action="" method="post">
			<p>メールアドレス</p>
			<input type="text" name="email" placeholder="メールアドレス" required>
			<p>名前</p>
			<input type="text" name="name" placeholder="名前" required>
			<p>件名</p>
			<input type="text" name="subject" placeholder="件名" required>
			<p>本文</p>
			<textarea id="body" name="body" cols="30" rows="10" required></textarea>
			<br>
			<button type="submit" name="submit">お問い合わせ内容を送信する</button>
		</form>
	</div>
</div>

<footer class="center">
	<div class="container">
		<div>
			<p>© 2020 shizuka</p>
		</div>
	</div>
</footer>
	
<!-- jqueryのCDN jsなのでbodyの閉じタグ直前に読み込む -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</body>
</html>
