<script src="https://www.google.com/recaptcha/api.js?render=6LedYN8ZAAAAAFuh-xKgM4Jztp6EUsTwQv0T9vDQ"></script>
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
