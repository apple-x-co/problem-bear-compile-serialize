{{ extends ('layout/Store/base') }}
{{ $storeHelpers = storeHelpers() }}

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
{{ if (isset($authError) && $authError): }}
{{= render('partials/Customer/AlertError', ['text' => 'Authentication Error']) }}
{{ endif }}
{{ if (isset($recaptchaError) && $recaptchaError): }}
{{= render('partials/Customer/AlertError', ['text' => 'reCAPTCHA Error']) }}
{{ endif }}

<form method="post">
    {{= $storeHelpers->textBox(form: $form, input: 'username') }}
    {{= $storeHelpers->errorMessage(form: $form, input: 'username') }}

    {{= $storeHelpers->textBox(form: $form, input: 'password') }}
    {{= $storeHelpers->errorMessage(form: $form, input: 'password') }}

    {{= googleRecaptchaV2(checked: 'googleRecaptchaChecked', expired: 'googleRecaptchaExpired', error: 'googleRecaptchaError') }}

    {{= $storeHelpers->submit(form: $form, input: 'login', attribs: ['id' => 'login', 'value' => 'LOGIN', 'disabled' => 'disabled']) }}
    {{= csrfTokenField(form: $form) }}
</form>
{{ endBlock () }}
