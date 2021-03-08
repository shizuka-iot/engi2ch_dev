//jqueryのdocument.readyを使うと
$(function(){
	grecaptcha.ready(function () {
		grecaptcha.execute('6LcOsHYaAAAAAB1Ga_gxj4jw42m_ulPBjb1XjXto', { action: 'contact' })
		.then(function (token) {
			var recaptchaResp = document.getElementById('recaptchaResponse');
			recaptchaResp.value = token;
		});
	});
});
