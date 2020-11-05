<script src="https://www.google.com/recaptcha/api.js?render=6LedYN8ZAAAAAFuh-xKgM4Jztp6EUsTwQv0T9vDQ"></script>
<div class="center main">
	<div class="container column center">
		<div class="contact_form_wrap center column">
			<h2>お問い合わせ</h2>
			<form action="" method="post" class="contact_form center column">
				<input type="hidden" name="recaptcha_response" id="recaptchaResponse">
				<br>
				<p>メールアドレス <span class="require">必須</span></p>
				<input type="text" name="email" placeholder="メールアドレス" required>

				<br>
				<br>
				<p>名前 <span class="require">必須</span></p>
				<input type="text" name="name" placeholder="名前" required>

				<br>
				<br>
				<p>件名 <span class="require">必須</span></p>
				<input type="text" name="subject" placeholder="件名" required>

				<br>
				<br>
				<p>本文 <span class="require">必須</span></p>
				<textarea id="body" name="body" cols="30" rows="10" required></textarea>
				<br>
				<button type="submit" name="submit">お問い合わせ内容を送信する</button>
			</form>
		</div>
	</div>
</div>
<script src="../js/recaptcha.js"></script>
