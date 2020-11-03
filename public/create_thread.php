<?php
require __DIR__.'/../config/config.php';

// インデックスコントローラーをインスタンス化。コンストラクタはない。
$IndexCtr = new \Mvc0623\Controller\Thread\Index();
$IndexCtr->run();
$categories = $IndexCtr->getCategoryInfo();

include 'views/layout/header.php';
include 'views/create_thread_view.php';
include 'views/layout/footer.php';
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
</script>
<script>
$(function(){
  $('#myfile').change(function(e){
    //ファイルオブジェクトを取得する
    var file = e.target.files[0];
    var reader = new FileReader();

    //画像でない場合は処理終了
    if(file.type.indexOf("image") < 0){
      alert("画像ファイルを指定してください。");
      return false;
    }

    //アップロードした画像を設定する
    reader.onload = (function(file){
      return function(e){
        $("#img1").attr("src", e.target.result);
        $("#img1").attr("title", file.name);
      };
    })(file);
    reader.readAsDataURL(file);

  });
});
</script>
</body>
</html>
