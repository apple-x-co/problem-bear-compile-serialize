{{ extends ('layout/Admin/base') }}

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
{{ $adminHelpers = adminHelpers() }}

{{ if (isset($authError) && $authError): }}
{{= render('partials/Admin/AlertError', ['text' => 'Authentication Error']) }}
{{ endif }}
{{ if (isset($recaptchaError) && $recaptchaError): }}
{{= render('partials/Admin/AlertError', ['text' => 'reCAPTCHA Error']) }}
{{ endif }}

<form method="post">
    {{= $adminHelpers->textBox(form: $form, input: 'username') }}
    {{= $adminHelpers->errorMessage(form: $form, input: 'username') }}

    {{= $adminHelpers->textBox(form: $form, input: 'password') }}
    {{= $adminHelpers->errorMessage(form: $form, input: 'password') }}

    {{= $adminHelpers->checkBox(form: $form, input: 'remember') }}
    {{= $adminHelpers->errorMessage(form: $form, input: 'remember') }}

    {{= googleRecaptchaV2(checked: 'googleRecaptchaChecked', expired: 'googleRecaptchaExpired', error: 'googleRecaptchaError') }}

    {{= $adminHelpers->submit(form: $form, input: 'login', attribs: ['id' => 'login', 'value' => 'LOGIN', 'disabled' => 'disabled']) }}
    {{= csrfTokenField(form: $form) }}
</form>
{{ endBlock () }}
