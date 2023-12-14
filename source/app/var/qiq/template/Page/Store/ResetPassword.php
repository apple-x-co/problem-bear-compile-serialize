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
    <div>
        <p>電話番号</p>
        {{= $storeHelpers->textBox(form: $form, input: 'phoneNumber') }}
        {{= $storeHelpers->errorMessage(form: $form, input: 'phoneNumber') }}
    </div>

    <div>
        <p>パスワード</p>
        {{= $storeHelpers->textBox(form: $form, input: 'password') }}
        {{= $storeHelpers->errorMessage(form: $form, input: 'password') }}
    </div>

    {{= googleRecaptchaV2(checked: 'googleRecaptchaChecked', expired: 'googleRecaptchaExpired', error: 'googleRecaptchaError') }}

    {{= $storeHelpers->submit(form: $form, input: 'continue', attribs: ['id' => 'continue', 'disabled' => 'disabled']) }}
    {{= $storeHelpers->submit(form: $form, input: 'signature') }}
    {{= csrfTokenField(form: $form) }}
</form>
{{ endBlock () }}
