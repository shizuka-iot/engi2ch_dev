$(function(){
	'use strict';

	$('.good_btn').on('click', function(){
		let id = $(this).data('good_id');
		console.log('goodボタン'+ id +'が押下されました');

		$.ajax
		(
			{
				url: '_ajax.php',
				type: 'post', 
				data:
				{
					id: id,
					mode: 'good',
					token: $('#token').val(),
				}
			}
		).done(function(data){
			console.log('通信成功');
			console.log(data.good);
			console.log(data.bad);
			$('#good_'+ id).next('.good').text(data.good);// これは成功した。
			// $('#good_'+ id).toggleClass('color_gr');// これは成功した。
			$('#bad_'+ id).next('.bad').text(data.bad);// これは成功した。
			// $('#bad_'+ id).toggleClass('color_bl');// これは成功した。
			// $(this).next('.good').text('test');
		// });
		}).fail(function(XMLHttpRequest, textStatus, errorThrown){
    console.log(XMLHttpRequest.status);
    console.log(textStatus);
    console.log(errorThrown);
		});
		return false;
	});
	$('.bad_btn').on('click', function(){
		let id = $(this).data('bad_id');
		console.log('badボタン'+ id +'が押下されました');

		$.ajax
		(
			{
				url: '_ajax.php',
				type: 'post', 
				data:
				{
					id: id,
					mode: 'bad',
					token: $('#token').val(),
				}
			}
		).done(function(data){
			console.log('通信成功');
			console.log(data.bad);
			/* カウントを更新 */
			$('#good_'+ id).next('.good').text(data.good);// これは成功した。
			$('#bad_'+ id).next('.bad').text(data.bad);// これは成功した。

			/* ボタンの色を更新 */
			// $('#good_'+ id).toggleClass('color_gr');// これは成功した。
			// $('#bad_'+ id).toggleClass('color_bl');// これは成功した。
		}).fail(function(XMLHttpRequest, textStatus, errorThrown){
    console.log(XMLHttpRequest.status);
    console.log(textStatus);
    console.log(errorThrown);
		});
		return false;
	});
});