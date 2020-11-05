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
