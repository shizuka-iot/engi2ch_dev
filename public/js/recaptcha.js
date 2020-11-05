<script>
//jqueryのdocument.readyを使うと
$(function(){
	grecaptcha.ready(function () {
		grecaptcha.execute('6LedYN8ZAAAAAFuh-xKgM4Jztp6EUsTwQv0T9vDQ', { action: 'contact' })
		.then(function (token) {
			var recaptchaResp = document.getElementById('recaptchaResponse');
			recaptchaResp.value = token;
		});
	});
});
</script>
