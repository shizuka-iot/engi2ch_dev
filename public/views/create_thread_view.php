<div class="center main">
	<div class="container column">
		<section class="create_thread_area">
			<h2><i class="fas fa-pencil-alt"></i>新規スレッド作成</h2>
			<div class="create_thread_form_wrap">
				<form class="column" method="post" action="#create_thread_form"
				 id="create_thread_form" enctype="multipart/form-data">
					
					<input type="hidden" name="token"
					 value="<?=h($_SESSION['token'])?>">


					<div class="input_item">
						<?php $IndexCtr->showError('thread_title')?>
						<p><span class="require">必須</span> タイトルを140文字以内で入力してください </p>
						<input type="text" name="thread_title" placeholder="タイトル (必須)"
						 value="<?=h($IndexCtr->getValue("thread_title"))?>">
					</div>

					<div class="input_item">
						<?php $IndexCtr->showError('cat_id')?>
						<p><span class="require">必須</span> カテゴリを選択してください</p>
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
					</div>

					<div class="input_item">
						<?php $IndexCtr->showError('thread_auther')?>
						<p><span class="optional">任意</span> 名前を50文字以内で入力してください。未入力の場合は"<?=h(ANONYMOUS)?>"と自動入力されます </p>
						<input type="text" name="thread_auther" placeholder="名前 (任意)"
						 value="<?=h($IndexCtr->getValue("thread_auther"))?>">
					</div>
					<div class="input_item">
						<?php $IndexCtr->showError('thread_body')?>
						<p><span class="require">必須</span> 本文を1000文字以内で入力してください </p>
						<textarea name="thread_body" placeholder="本文 (必須)"><?=h($IndexCtr->getValue("thread_body"))?></textarea>
					</div>

					<div class="input_item">
						<?php $IndexCtr->showError('img')?>
						<p><span class="require">必須</span> 画像を選択してください </p>
						<input type="file" name="thread_img" accept="image/*" id="myfile">
					</div>

					<div class="input_item">
						<button type="submit" name="create_thread">スレッドを作成する<i class="fas fa-pencil-alt"></i></button><br>
					</div>

				</form>
			</div>
		</section>
	</div>
</div>
