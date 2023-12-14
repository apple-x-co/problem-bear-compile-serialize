{{ extends ('layout/Salon/base') }}

{{ setBlock ('body') }}
{{ $salonHelpers = salonHelpers() }}

{{ if (isset($authError) && $authError): }}
    {{= render('partials/Salon/AlertError', ['text' => 'Authentication Error']) }}
{{ endif }}

<form method="post">
{{= $salonHelpers->textBox(form: $form, input: 'password') }}
{{= $salonHelpers->errorMessage(form: $form, input: 'password') }}
{{= $salonHelpers->submit(form: $form, input: 'continue', attribs: ['value' => 'CONTINUE']) }}
{{= csrfTokenField(form: $form) }}
</form>
{{ endBlock () }}
