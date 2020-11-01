<?php
require __DIR__.'/../config/config.php';
?>

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
		<main>
			<form action="" method="get">
				<select id="" name="period">
					<option value="">新しい順</option>
					<option value="">古い順</option>
				</select>

				<select id="" name="comments">
					<option value="">コメントの多い順</option>
					<option value="">コメントの少ない順</option>
				</select>

			</form>
		</main>
	</div>
</div>

<footer class="center">
	<div class="container">
		<a href="contact.php">
			<p>お問い合わせ</p>
		</a>
		<div>
			<p>© 2020 shizuka</p>
		</div>
	</div>
</footer>
	
<!-- jqueryのCDN jsなのでbodyの閉じタグ直前に読み込む -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="vote.js"></script>
<script>
// スムーススクロールのコード。
// 完全にコピペだけで動く。
// html側で通常通り移動元のaタグに#移動先idを指定するだけで全て動作するようになる。
$(function(){
  $('a[href^="#"]').click(function(){
    var speed = 400;
    var href= $(this).attr("href");
    var target = $(href == "#" || href == "" ? 'html' : href);
    var position = target.offset().top;
    $("html, body").animate({scrollTop:position}, speed, "swing");
    return false;
  });
});
</script>
</body>
</html>
