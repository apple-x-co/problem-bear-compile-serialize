{{ extends ('layout/Salon/base') }}

{{ setBlock ('head_scripts') }}
    {{ parentBlock () }}
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        function googleRecaptchaChecked() {
            document.getElementById('login').removeAttribute('disabled');
        }
        function googleRecaptchaExpired() {
            document.getElementById('login').setAttribute('disabled', 'disabled');
        }
        function googleRecaptchaError() {
            document.getElementById('login').setAttribute('disabled', 'disabled');
        }
    </script>
{{ endBlock () }}

{{ setBlock ('body') }}
{{ $salonHelpers = salonHelpers() }}

{{ if (isset($authError) && $authError): }}
{{= render('partials/Salon/AlertError', ['text' => 'Authentication Error']) }}
{{ endif }}
{{ if (isset($recaptchaError) && $recaptchaError): }}
{{= render('partials/Salon/AlertError', ['text' => 'reCAPTCHA Error']) }}
{{ endif }}

<form method="post">
    {{= $salonHelpers->textBox(form: $form, input: 'username') }}
    {{= $salonHelpers->errorMessage(form: $form, input: 'username') }}

    {{= $salonHelpers->textBox(form: $form, input: 'password') }}
    {{= $salonHelpers->errorMessage(form: $form, input: 'password') }}

    {{= $salonHelpers->checkBox(form: $form, input: 'remember') }}
    {{= $salonHelpers->errorMessage(form: $form, input: 'remember') }}

    {{= googleRecaptchaV2(checked: 'googleRecaptchaChecked', expired: 'googleRecaptchaExpired', error: 'googleRecaptchaError') }}

    {{= $salonHelpers->submit(form: $form, input: 'login', attribs: ['id' => 'login', 'value' => 'LOGIN', 'disabled' => 'disabled']) }}
    {{= csrfTokenField(form: $form) }}
</form>
{{ endBlock () }}
