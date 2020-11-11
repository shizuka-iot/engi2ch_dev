$(function(){
	'use strict';

	/* 
	 * グッドボタンがクリックされたら 
	 * その要素のdata属性であるgood_idの値を取得
	 */
	$('.good_btn').on('click', function(){

		let id = $(this).data('good_id');
		if (id)
		{
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

				$('#good_'+ id).next('.good').text(data.good);
				$('#bad_'+ id).next('.bad').text(data.bad);

			}).fail(function(XMLHttpRequest, textStatus, errorThrown){
			console.log(XMLHttpRequest.status);
			console.log(textStatus);
			console.log(errorThrown);
			});
			return false;
		}
		else
		{
			let thread_no = $(this).data('good_thread');
			$.ajax
			(
				{
					url: '_ajax.php',
					type: 'post', 
					data:
					{
						thread_no: thread_no,
						mode: 'good',
						token: $('#token').val(),
					}
				}
			).done(function(data){

				$('#good_thread_'+ thread_no).next('.good').text(data.good);
				$('#bad_thread_'+ thread_no).next('.bad').text(data.bad);
				$('#number_of_vote').text(Number(data.good)+Number(data.bad));

				circle.sectorInfo = [
						["#b5b5b5", "bad", Number(data.bad)],
						["#25b7c0", "good", Number(data.good)],
				];
				circle.restart();

			}).fail(function(XMLHttpRequest, textStatus, errorThrown){
			console.log(XMLHttpRequest.status);
			console.log(textStatus);
			console.log(errorThrown);
			});
			return false;
		}


	});
	$('.bad_btn').on('click', function(){
		let id = $(this).data('bad_id');
		if (id)
		{
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
				$('#good_'+ id).next('.good').text(data.good);
				$('#bad_'+ id).next('.bad').text(data.bad);

			}).fail(function(XMLHttpRequest, textStatus, errorThrown){
			console.log(XMLHttpRequest.status);
			console.log(textStatus);
			console.log(errorThrown);
			});
			return false;
		}
		else
		{
			let thread_no = $(this).data('bad_thread');
			$.ajax
			(
				{
					url: '_ajax.php',
					type: 'post', 
					data:
					{
						thread_no: thread_no,
						mode: 'bad',
						token: $('#token').val(),
					}
				}
			).done(function(data){
				$('#good_thread_'+ thread_no).next('.good').text(data.good);
				$('#bad_thread_'+ thread_no).next('.bad').text(data.bad);
				$('#number_of_vote').text(Number(data.good)+Number(data.bad));

				/* 円グラフの値を再送信 */
				circle.sectorInfo = [
						["#b5b5b5", "bad", Number(data.bad)],
						["#25b7c0", "good", Number(data.good)],
				];
				/* 円グラフのクラスを再計算 */
				circle.restart();
			}).fail(function(XMLHttpRequest, textStatus, errorThrown){
			console.log(XMLHttpRequest.status);
			console.log(textStatus);
			console.log(errorThrown);
			});
			return false;
		}
	});
});
