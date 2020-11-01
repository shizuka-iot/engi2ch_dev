<?php
require __DIR__.'/../config/config.php';

// インデックスコントローラーをインスタンス化。コンストラクタはない。
$IndexCtr = new \Mvc0623\Controller\Thread\Index();

$Page = new \Mvc0623\Controller\Thread\Page();

$IndexCtr->run();
$categories = $IndexCtr->getCategoryInfo();

// スレッド情報を取り出し。
// インデックスコントローラーのメソッドを呼び出してるが定義されているのは親クラス。
$threads = $IndexCtr->getThreads();

include 'views/index_view.php';
?>

	
<!-- jqueryのCDN jsなのでbodyの閉じタグ直前に読み込む -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="vote.js"></script>
<script>
// スムーススクロールのコード。
// 完全にコピペだけで動く。
// html側で通常通り移動元のaタグに#移動先idを指定するだけで全て動作するようになる。
$(function(){
  $('a[href^="#"]').click(function(){
    var speed = 400;
    var href= $(this).attr("href");
    var target = $(href == "#" || href == "" ? 'html' : href);
    var position = target.offset().top;
    $("html, body").animate({scrollTop:position}, speed, "swing");
    return false;
  });
});
$(function() {
  $('input[type=file]').after('<span></span>');

  // アップロードするファイルを選択
  $('input[type=file]').change(function() {
    var file = $(this).prop('files')[0];

    // 画像以外は処理を停止
    if (! file.type.match('image.*')) {
      // クリア
      $(this).val('');
      $('span').html('');
      return;
    }

    // 画像表示
    var reader = new FileReader();
    reader.onload = function() {
      var img_src = $('<img>').attr('src', reader.result);
      $('span').html(img_src);
    }
    reader.readAsDataURL(file);
  });
});
</script>
</body>
</html>
