<?php
require __DIR__.'/../config/config.php';

// インデックスコントローラーをインスタンス化。コンストラクタはない。
$IndexCtr = new \Mvc0623\Controller\Thread\Index();
$IndexCtr->run();
$categories = $IndexCtr->getCategoryInfo();

include 'views/layout/head.php';
include 'views/layout/header.php';
include 'views/create_thread_view.php';
include 'views/layout/footer.php';
?>
