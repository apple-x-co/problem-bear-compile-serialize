{{ extends ('layout/Store/public-page') }}
{{ $storeHelpers = storeHelpers() }}

{{ setBlock ('title') }}新規会員登録 | {{ parentBlock () }}{{ endBlock () }}

{{ setBlock ('body_content') }}
<form method="post">
    <div>
        <p>性別</p>
        {{= $storeHelpers->textBox(form: $form, input: 'genderType') }}
        {{= $storeHelpers->errorMessage(form: $form, input: 'genderType') }}
    </div>

    <div>
        <p>名前(氏名)</p>
        {{= $storeHelpers->textBox(form: $form, input: 'familyName') }}
        {{= $storeHelpers->textBox(form: $form, input: 'givenName') }}
        {{= $storeHelpers->errorMessage(form: $form, input: 'familyName') }}
        {{= $storeHelpers->errorMessage(form: $form, input: 'givenName') }}
    </div>

    <div>
        <p>名前(カナ)</p>
        {{= $storeHelpers->textBox(form: $form, input: 'phoneticFamilyName') }}
        {{= $storeHelpers->textBox(form: $form, input: 'phoneticGivenName') }}
        {{= $storeHelpers->errorMessage(form: $form, input: 'phoneticFamilyName') }}
        {{= $storeHelpers->errorMessage(form: $form, input: 'phoneticGivenName') }}
    </div>

    <div>
        <p>電話番号</p>
        {{= $storeHelpers->textBox(form: $form, input: 'phoneNumber') }}
        {{= $storeHelpers->errorMessage(form: $form, input: 'phoneNumber') }}
    </div>

    <div>
        <p>メールアドレス</p>
        {{= $storeHelpers->textBox(form: $form, input: 'email') }}
        {{= $storeHelpers->errorMessage(form: $form, input: 'email') }}
    </div>

    <div>
        <p>パスワード</p>
        {{= $storeHelpers->textBox(form: $form, input: 'password') }}
        {{= $storeHelpers->errorMessage(form: $form, input: 'password') }}
    </div>

    <div>
        <p>同意</p>
        {{= $storeHelpers->textBox(form: $form, input: 'agree') }}
        {{= $storeHelpers->errorMessage(form: $form, input: 'agree') }}
    </div>

    <div>
        {{= csrfTokenField($form) }}
        {{= $storeHelpers->submit(form: $form, input: 'continue') }}
        {{= $storeHelpers->errorMessage(form: $form, input: 'continue') }}
    </div>
</form>
{{ endBlock () }}
