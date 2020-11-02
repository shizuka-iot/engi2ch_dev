
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

		<section class="main_contents">

			<!-- スレッド一覧か個別スレッドを表示するエリア -->
			<div class="thread_area">


				<!-- 個別スレッドページ -->
				<?php if( isset($_GET['thread']) ):?>
					<?php $thread = $IndexCtr->getThreadFromNo();?>
					<div class="thread">

						<?php if (!$thread):?>
						<p>記事が存在しません</p>
						<?php else:?>
						<h3 class="fw_normal"><?=h($thread->title)?><span class="bold">【<?=h($thread->cat_name)?>】</span></h3><span><?=h($thread->created_at)?></span>
						

						<div class="">
							<div class="thread_contents">
								<img src="<?=h($IndexCtr->getImgUrl($thread->fileName, 0))?>">
								<p>名前: <span class="color_lb fs_18"><?=h($thread->auther)?></span><p>
								<p><?=nl2br(h($thread->body))?><p>
							</div>
						</div>

						<div class="reply_area">

							<div class="replies">
								<?php $replies = $IndexCtr->getReplies($thread->no); $index=1;?>
								<?php foreach($replies as $reply):?>
									<div class="each_reply">
										<p>
											<?=h($index)?>.名前:
												<span class="color_gr fs_18"><?=h($reply->auther)?>
												</span>
											投稿日時: <?=h($reply->created_at)?>
											<i class="fas fa-reply"></i>返信
										</p>
								
										<p class="reply_body"><?=nl2br(h($reply->body))?></p>


										<!-- formタグで囲まないのはここでPOSTする必要がない・ここでPOSTしてはいけないから。JSでPOSTは操作する。ここでPOSTするとページ遷移してしまい非同期ではなくなる。 -->
											<i class="fas fa-thumbs-up good_btn" id="good_<?=$reply->no?>" data-good_id="<?=$reply->no?>"></i><span class="good"><?=h($reply->good)?></span><i class="fas fa-thumbs-down bad_btn" id="bad_<?=$reply->no?>" data-bad_id="<?=$reply->no?>"></i><span class="bad"><?=h($reply->bad)?></span>

									</div>
									<?php $index++;?>
								<?php endforeach;?>
							</div>
							<h4>コメントする</h4>
							<form method="post" action="#jump" id="jump" class="reply_form">
								<input type="hidden" name="token"
								 value="<?=h($_SESSION['token'])?>">

								<input type="hidden" name="thread_no"
								 value="<?=h($thread->no)?>">

								<input type="hidden" name="user_id"
								value="<?php isset($_SESSION['me']->id) ?
												 $_SESSION['me']->id: null ?>">

								<input type="text" name="reply_auther" placeholder="名前"
								 value="<?=h($IndexCtr->getValue("reply_auther"))?>">

								<?php $IndexCtr->showError('reply_body')?>
								<textarea name="reply_body" placeholder="本文"><?=h($IndexCtr->getValue("reply_body"))?></textarea>
								<br>

								<button type="submit" name="reply">コメントする</button><br>
							</form>
						</div>
						<?php endif;?>

					</div>

				<?php else:?>

					<!-- スレッド一覧 -->
					<section id="sort_section">
						<form method="get" action="" class="sort_form">
							<div class="sort_wrap">
								<div class="sort">
									<label for="sort_period" class="fs_14">並べ替え:</label>
									<select id="sort_period" name="">
										<option value="">新着順</option>
										<option value="">古い順</option>
										<option value="">評価の高い順</option>
										<option value="">コメントの多い順</option>
									</select>

								</div>

								<div class="sort">
									<label for="sort_category" class="fs_14">カテゴリ:</label>
									<select id="" name="category">
										<option value="">全カテゴリ</option>
										<?php foreach($categories as $category):?>
										<option value="<?=h($category->cat_name)?>" <?php if(isset($_GET['category']) && $_GET['category']===$category->cat_name)echo 'selected';?>><?=h($category->cat_name)?></option>
												
										<?php endforeach;?>
									</select>
								</div>

							</div>
							<div class="sort_button_wrap">
								<button class="sort_button">条件を選択して並び替え</button>
							</div>
						</form>
					</section>

					<section class="page">
						<?php $Page->showPaging()?>
					</section>

					<?php if ($threads):?>
					<?php foreach($threads as $thread):?>
					<a class="each_page_link" href="<?=h(SITE_URL."?thread=".$thread->no)?>">
						<div class="thread row">

							<div class="thread_left">
								<img src="<?=h($IndexCtr->getImgUrl($thread->fileName, $thread->thumbnail_flag))?>">
							</div>

							<div class="thread_right">
								<div class="thread_contents column between">
									<h3 class="fw_bold fs_16 color_co"><?=h($thread->title)?></h3>
									<p class="fs_12 color_lb2">
										<span class="fs_13 color_lb2">
											<i class="fas fa-comments"></i>
											<?=h($IndexCtr->getCommentsFromThreadNo($thread->no))?>コメント
										</span>
										<span class="bold">【<?=h($thread->cat_name)?>】</span>
									</p>
								</div>

							</div>

						</div>
					</a>
					<?php endforeach;?>
					<?php endif;?>

					<section class="page">
						<?php $Page->showPaging()?>
					</section>

				<?php endif;?>
			</div>

			<!-- 右側にあるナビゲーションバー -->
			<div class="column">
				<div class="side column">
				<?php if( !isset($_GET['thread']) ):?>
					<a href="create_thread.php" id="jump_create_thread_form">
					<div class="jump_create_thread center">
						スレッドを作成する<i class="fas fa-pencil-alt"></i>
					</div>
				<?php endif;?>
					</a>
					<nav class="column">
						<div class="search_form_wrap">
							<form method="get" action="" class="row">
								<input type="text" name="search" size="1" placeholder="スレタイを検索">
								<button type="submit"><i class="fas fa-search"></i></button>
							</form>
						</div>
						<a href="search.php">検索ページ</a>
						<h3>カテゴリ</h3>
						<ul>
							<?php foreach($categories as $category):?>
								<a href="?category=<?=h($category->cat_name)?>"><li class="color_lb">
								<?=h($category->cat_name.' ('.$IndexCtr->getCountCategoryFromId($category->id).')')?>
								</li></a>
							<?php endforeach;?>
						</ul>
					</nav>
				</div>
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
