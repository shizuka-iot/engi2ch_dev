<script src="js/circle.js">
</script>
<div class="header_search_word_form_wrapper">
	<div class="container">
		<form method="get" action="" class="row header_search_word_form">
			<input type="text" name="search" size="1" placeholder="スレタイを検索">
			<button type="submit"><i class="fas fa-search"></i></button>
		</form>
	</div>
</div>

<div class="center main">
	<div class="container column">

		<section class="main_contents">

			<!-- スレッド一覧か個別スレッドを表示するエリア -->
			<div class="thread_area">


				<!-- 個別スレッドページ -->
				<?php if( isset($_GET['thread']) ):?>
					<?php $thread = $IndexCtr->getThreadFromNo();?>
					<?php
					if (isset($thread) && $thread !== false)
					{
						$arr = [
							["#b5b5b5", "bad", (int)h($thread->bad)],
							["#25b7c0", "good", (int)h($thread->good)],
						];
						$sectorInfo = json_encode($arr);
					}
					?>
					<div class="thread">

						<?php if (!$thread):?>
						<p>記事が存在しません</p>
						<?php else:?>
						<h2 class="fw_normal"><span class="bold">【<?=h($thread->cat_name)?>】</span><?=h($thread->title)?></h2>
						

						<div class="">
							<div class="thread_contents column">

								<div class="center">
									<img src="<?=h($IndexCtr->getImgUrl($thread->fileName, 0))?>">
								</div>

								<div class="text_and_vote">
									<div class="text_contents_area">
										<p>
											1.名前:
												<span class="color_gr fs_18"><?=h($thread->auther)?>
												</span>
											投稿日時: <?=h($thread->created_at)?>
											<!-- <i class="fas fa-reply"></i>返信 -->
										</p>
										<p class="thread_body"><?=nl2br(h($thread->body))?></p>
									</div>
									<div class="thread_vote">
										<div class="thread_vote_area row between">
											<!-- formタグで囲まないのはここでPOSTする必要がない
											・ここでPOSTしてはいけないから。JSでPOSTは操作する。
												ここでPOSTするとページ遷移してしまい非同期ではなくなる。 -->
											<div class="good_btn_wrapeer">
												<i class="fas fa-thumbs-up good_btn fs_24" id="good_thread_<?=$thread->no?>" data-good_thread="<?=$thread->no?>"></i>
												<span class="good fs_20"><?=h($thread->good)?></span>
											</div>

											<div class="bad_btn_wrapper">
												<i class="fas fa-thumbs-down bad_btn fs_24" id="bad_thread_<?=$thread->no?>" data-bad_thread="<?=$thread->no?>"></i>
												<span class="bad fs_20"><?=h($thread->bad)?></span>
											</div>

										</div>
										<canvas id="can" width="200" height="180"></canvas>
										<p class="fs_14">■ <span id="number_of_vote"><?=h((int)$thread->good+(int)$thread->bad)?></span>人が評価しました</p>
									</div>
								</div>


							</div>
						</div>

						<div class="reply_area">

							<div class="replies">
								<?php $replies = $IndexCtr->getReplies($thread->no); $index=2;?>
								<?php foreach($replies as $reply):?>
									<div class="each_reply column">
										<p>
											<?=h($index)?>.名前:
												<span class="color_gr fs_18"><?=h($reply->auther)?>
												</span>
											投稿日時: <?=h($reply->created_at)?>
											<!-- <i class="fas fa-reply"></i>返信 -->
										</p>
								
										<p class="reply_body"><?=nl2br(h($reply->body))?></p>


										<div class="vote_area">
											<!-- formタグで囲まないのはここでPOSTする必要がない
											・ここでPOSTしてはいけないから。JSでPOSTは操作する。
												ここでPOSTするとページ遷移してしまい非同期ではなくなる。 -->
											<i class="fas fa-thumbs-up good_btn" id="good_<?=$reply->no?>" data-good_id="<?=$reply->no?>"></i>
											<span class="good"><?=h($reply->good)?></span>
											<i class="fas fa-thumbs-down bad_btn" id="bad_<?=$reply->no?>" data-bad_id="<?=$reply->no?>"></i>
											<span class="bad"><?=h($reply->bad)?></span>
										</div>

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

								<div class="reply_input_wrap">
									<p class="fs_12">名前を入力してください。</p>
									<p class="fs_12">未入力の場合は"<?=h(ANONYMOUS)?>"と自動入力されます。</p>
									<input type="text" name="reply_auther" placeholder="名前"
									 value="<?=h($IndexCtr->getValue("reply_auther"))?>">
								</div>
								<div class="reply_input_wrap">
									<p class="fs_12">コメントを1000文字以内で入力してください。</p>
									<?php $IndexCtr->showError('reply_body')?>
									<textarea name="reply_body" placeholder="本文"><?=h($IndexCtr->getValue("reply_body"))?></textarea>
								</div>

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
									<label for="sort_category" class="fs_14">カテゴリ:</label>
									<select id="" name="category">
										<option value="">全カテゴリ</option>
										<?php foreach($categories as $category):?>
										<option value="<?=h($category->cat_name)?>" <?php if(isset($_GET['category']) && $_GET['category']===$category->cat_name)echo 'selected';?>><?=h($category->cat_name)?></option>
												
										<?php endforeach;?>
									</select>
								</div>

								<div class="sort">
									<label for="sort_period" class="fs_14">並び替え:</label>
									<select id="sort_period" name="sort">
										<option value="popular" <?php if(isset($_GET['sort']) && $_GET['sort']==='popular')echo 'selected';?>>人気順</option>
										<option value="new" <?php if(isset($_GET['sort']) && $_GET['sort']==='new')echo 'selected';?>>新着順</option>
										<option value="comment" <?php if(isset($_GET['sort']) && $_GET['sort']==='comment')echo 'selected';?>>コメントの多い順</option>
										<option value="old" <?php if(isset($_GET['sort']) && $_GET['sort']==='old')echo 'selected';?>>古い順</option>
									</select>

								</div>

							</div>
							<div class="sort_button_wrap">
								<button class="sort_button">並び替え実行</button>
							</div>
						</form>
					</section>

					<section class="page">
						<?php $Page->showPaging()?>
					</section>

					<?php if ($threads):?>
					<?php foreach($threads as $thread):?>
					<a class="each_page_link" href="<?=h(SITE_URL."?thread=".$thread->no)?>">
						<div class="each_thread row">

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
			<div class="side_wrap column sticky">
				<div class="side">

					<?php if( !isset($_GET['thread']) ):?>
						<div class="jump_create_thread_wrap">
							<a href="create_thread.php" id="jump_create_thread_form" class="color_wh">
								<div class="jump_create_thread center">
										スレッドを作成する<i class="fas fa-pencil-alt"></i>
								</div>
							</a>
						</div>
					<?php endif;?>

					<div class="new_threads_area">
						<h2 class="color_lb fw_400 fs_18">新着スレッド</h2>
						<?php foreach ($newThreads as $newThread):?>

							<a class="each_page_link" href="<?=h(SITE_URL."?thread=".$newThread->no)?>">
								<div class="each_thread row">

									<div class="thread_left">
										<img src="<?=h($IndexCtr->getImgUrl($newThread->fileName, $newThread->thumbnail_flag))?>">
									</div>

									<div class="thread_right">
										<div class="thread_contents column between">
											<h3 class="fw_bold fs_14 color_co"><?=h($newThread->title)?></h3>
											<p class="fs_12 color_lb2">
												<span class="fs_12 color_lb2">
													<i class="fas fa-comments"></i>
													<?=h($IndexCtr->getCommentsFromThreadNo($newThread->no))?>コメント
												</span>
												<br>
												<span class="bold fs_12">【<?=h($newThread->cat_name)?>】</span>
											</p>
										</div>

									</div>

								</div>
							</a>
							
						<?php endforeach;?>
					</div>
					<div class="hot_topics_area">
						<h2 class="color_lb fw_400 fs_18">話題のスレッド</h2>
						<?php foreach ($hotTopics as $hotTopic):?>

							<a class="each_page_link" href="<?=h(SITE_URL."?thread=".$hotTopic->no)?>">
								<div class="each_thread row">

									<div class="thread_left">
										<img src="<?=h($IndexCtr->getImgUrl($hotTopic->fileName, $hotTopic->thumbnail_flag))?>">
									</div>

									<div class="thread_right">
										<div class="thread_contents column between">
											<h3 class="fw_bold fs_14 color_co"><?=h($hotTopic->title)?></h3>
											<p class="fs_12 color_lb2">
												<span class="fs_12 color_lb2">
													<i class="fas fa-comments"></i>
													<?=h($IndexCtr->getCommentsFromThreadNo($hotTopic->no))?>コメント
												</span>
												<br>
												<span class="bold fs_12">【<?=h($hotTopic->cat_name)?>】</span>
											</p>
										</div>

									</div>

								</div>
							</a>
							
						<?php endforeach;?>
					</div>

					<div class="ad center">
<!-- Rakuten Widget FROM HERE --><script type="text/javascript">rakuten_affiliateId="0ea62065.34400275.0ea62066.204f04c0";rakuten_items="ranking";rakuten_genreId="100026";rakuten_recommend="off";rakuten_design="slide";rakuten_size="250x250";rakuten_target="_blank";rakuten_border="off";rakuten_auto_mode="on";rakuten_adNetworkId="a8Net";rakuten_adNetworkUrl="https%3A%2F%2Frpx.a8.net%2Fsvt%2Fejp%3Fa8mat%3D3BQKJ8%2BDG1FLE%2B2HOM%2BBS629%26rakuten%3Dy%26a8ejpredirect%3D";rakuten_pointbackId="a20111306312_3BQKJ8_DG1FLE_2HOM_BS629";rakuten_mediaId="20011816";</script><script type="text/javascript" src="//xml.affiliate.rakuten.co.jp/widget/js/rakuten_widget.js"></script><!-- Rakuten Widget TO HERE -->
<img border="0" width="1" height="1" src="https://www16.a8.net/0.gif?a8mat=3BQKJ8+DG1FLE+2HOM+BS629" alt="">
					</div>

					<nav class="column">
						<div class="search_form_wrap">
							<form method="get" action="" class="row">
								<input type="text" name="search" size="1" placeholder="スレタイを検索">
								<button type="submit"><i class="fas fa-search"></i></button>
							</form>
						</div>
						<h3>カテゴリ</h3>
						<ul class="select_category_list">
							<?php foreach($categories as $category):?>
								<li>
								<a class="color_lb" href="?category=<?=h($category->cat_name)?>">
								<?=h($category->cat_name.' ('.$IndexCtr->getCountCategoryFromId($category->id).')')?>
								</a>
								</li>
							<?php endforeach;?>
						</ul>
					</nav>
				</div>
			</div>
			

		</section>

	</div>
</div>

