<?php
require __DIR__.'/../config/config.php';

include 'views/layout/head.php';
include 'views/layout/header.php';

echo <<<EOL
<div class="center main">
	<div class="container column">
		<h1 class="fw_normal">
			404 NOT FOUND
			<br>
			お探しのページは存在しません
		</h1>
	</div>
</div>
EOL;

include 'views/layout/footer.php';
?>

	
<!-- jqueryのCDN jsなのでbodyの閉じタグ直前に読み込む -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</body>
</html>
