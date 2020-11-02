<div class="center main">
	<div class="container column">
		<h2>お問い合わせ</h2>
		<form action="" method="post">
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

<!-- jqueryのCDN jsなのでbodyの閉じタグ直前に読み込む -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</body>
</html>
