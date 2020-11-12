$(function() {
  $('input[type=file]').after('<div class="show_upload_img"></div>');

  // アップロードするファイルを選択
  $('input[type=file]').change(function() {
    var file = $(this).prop('files')[0];

    // 画像以外は処理を停止
    if (! file.type.match('image.*')) {
      // クリア
      $(this).val('');
      $('.show_upload_img').html('');
      return;
    }

    // 画像表示
    var reader = new FileReader();
    reader.onload = function() {
      var img_src = $('<img>').attr('src', reader.result);
      $('.show_upload_img').html(img_src);
    }
    reader.readAsDataURL(file);
  });
});
