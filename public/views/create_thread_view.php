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
		<section class="create_thread_area">
			<h2><i class="fas fa-plus"></i>新規スレッド作成</h2>
			<div class="create_thread_form_wrap">
				<form class="column" method="post" action="#create_thread_form"
				 id="create_thread_form" enctype="multipart/form-data">
					
					<input type="hidden" name="token"
					 value="<?=h($_SESSION['token'])?>">

					<?php $IndexCtr->showError('thread_title')?>
					<input type="text" name="thread_title" placeholder="タイトル (必須)"
					 value="<?=h($IndexCtr->getValue("thread_title"))?>">

					<?php $IndexCtr->showError('cat_id')?>
					<p>・カテゴリを選択してください (必須)</p>
					<div class="select_category row wrap">
					<?php foreach($categories as $category):?>
						<div class="each_category">
						<label>
						<input type="radio" name="cat_id"
						value="<?=h($category->id)?>" <?php if( $IndexCtr->getValue('cat_id') === $category->id)echo 'checked';?>>
							<?=h($category->cat_name)?></label>
						</div>
					<?php endforeach;?>
					</div>

					<?php $IndexCtr->showError('thread_auther')?>
					<input type="text" name="thread_auther" placeholder="名前 (任意)"
					 value="<?=h($IndexCtr->getValue("thread_auther"))?>">

					<?php $IndexCtr->showError('thread_body')?>
					<textarea name="thread_body" placeholder="本文 (必須)"><?=h($IndexCtr->getValue("thread_body"))?></textarea>
					<br>

					<?php $IndexCtr->showError('img')?>
					<p>・画像を選択してください (必須)</p>
					<input type="file" name="thread_img" accept="image/*" id="myfile"><br>
				  <img id="img1" style="width:200px;height:auto;" />

					<button type="submit" name="create_thread">スレッドを作成する<i class="far fa-plus-square"></i></button><br>

				</form>
			</div>
		</section>
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
