<?php
define('ANONYMOUS', '匿名エンジニアさん');

/* SITE */
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
	$http = 'https://';
} else {
	$http = 'http://';
}
define('SITE_URL', $http . $_SERVER['HTTP_HOST']);
define('SITE_TITLE', 'エンジニちゃんねる');
define('SITE_SUBTITLE', 'エンジニアのための匿名掲示板');

/* 画像アップロード関連 */
define('IMGS_DIR', __DIR__.'/../public/imgs');
define('THUMBS_DIR', __DIR__.'/../public/thumbs');
define('THUMB_W', '240');
define('MAX_FILE_SIZE', 3*1024*1024);

/* ページ */
define('THREADS_PER_PAGE', 25);
