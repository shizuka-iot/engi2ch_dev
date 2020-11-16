<?php
require __DIR__.'/../config/config.php';

// インデックスコントローラーをインスタンス化。コンストラクタはない。
$IndexCtr = new \Mvc0623\Controller\Thread\Index();

// ページネーションクラスインスタンス化
$Page = new \Mvc0623\Controller\Thread\Page();

$IndexCtr->run();

// カテゴリ情報を取得
$categories = $IndexCtr->getCategoryInfo();

// スレッド情報を取り出し。
// インデックスコントローラーのメソッドを呼び出してるが定義されているのは親クラス。
$threads = $IndexCtr->getThreads();
$hotTopics = $IndexCtr->getHotTopics(5);
$newThreads = $IndexCtr->getNewThreads(5);

include 'views/layout/head.php';
include 'views/layout/header.php';
include 'views/index_view.php';
include 'views/layout/footer.php';
?>

	
