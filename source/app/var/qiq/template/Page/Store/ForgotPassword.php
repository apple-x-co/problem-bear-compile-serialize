{{ extends ('layout/Store/base') }}
{{ $storeHelpers = storeHelpers() }}

{{ setBlock ('head_scripts') }}
{{ parentBlock () }}
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
  function googleRecaptchaChecked() {
    document.getElementById('continue').removeAttribute('disabled');
  }
  function googleRecaptchaExpired() {
    document.getElementById('continue').setAttribute('disabled', 'disabled');
  }
  function googleRecaptchaError() {
    document.getElementById('continue').setAttribute('disabled', 'disabled');
  }
</script>
{{ endBlock () }}

{{ setBlock ('body') }}
{{ if (isset($recaptchaError) && $recaptchaError): }}
{{= render('partials/Customer/AlertError', ['text' => 'reCAPTCHA Error']) }}
{{ endif }}

<form method="post">
    {{= $storeHelpers->textBox(form: $form, input: 'email') }}
    {{= $storeHelpers->errorMessage(form: $form, input: 'email') }}

    {{= googleRecaptchaV2(checked: 'googleRecaptchaChecked', expired: 'googleRecaptchaExpired', error: 'googleRecaptchaError') }}

    {{= $storeHelpers->submit(form: $form, input: 'continue', attribs: ['id' => 'continue', 'disabled' => 'disabled']) }}
    {{= csrfTokenField(form: $form) }}
</form>
{{ endBlock () }}
