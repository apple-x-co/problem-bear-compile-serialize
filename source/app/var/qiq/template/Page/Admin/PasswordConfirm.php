{{ extends ('layout/Admin/base') }}

{{ setBlock ('body') }}
{{ $adminHelpers = adminHelpers() }}

{{ if (isset($authError) && $authError): }}
    {{= render('partials/Admin/AlertError', ['text' => 'Authentication Error']) }}
{{ endif }}

<form method="post">
{{= $adminHelpers->textBox(form: $form, input: 'password') }}
{{= $adminHelpers->errorMessage(form: $form, input: 'password') }}
{{= $adminHelpers->submit(form: $form, input: 'continue', attribs: ['value' => 'CONTINUE']) }}
{{= csrfTokenField(form: $form) }}
</form>
{{ endBlock () }}
