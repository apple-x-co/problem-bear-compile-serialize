{{ extends ('layout/Store/public-page') }}
{{ $storeHelpers = storeHelpers() }}

{{ setBlock ('title') }}新規会員登録 | {{ parentBlock () }}{{ endBlock () }}

{{ setBlock ('body_content') }}
<form method="post">
    <div>
        <p>性別</p>
        {{ $genderTypes = $storeHelpers->options(form: $form, input: 'genderType') }}
        {{= $genderTypes[$signUp->genderType] }}
    </div>

    <div>
        <p>名前(氏名)</p>
        {{= $signUp->familyName }} {{= $signUp->givenName }}
    </div>

    <div>
        <p>名前(カナ)</p>
        {{= $signUp->phoneticFamilyName }} {{= $signUp->phoneticGivenName }}
    </div>

    <div>
        <p>電話番号</p>
        {{= $signUp->phoneNumber }}
    </div>

    <div>
        <p>メールアドレス</p>
        {{= $signUp->email }}
    </div>

    <div>
        <p>パスワード</p>
        ********
    </div>

    <div>
        {{= $storeHelpers->submit(form: $form, input: 'back', attribs: ['formaction' => '../sign-up']) }}
        {{= $storeHelpers->submit(form: $form, input: 'complete', attribs: ['formaction' => './confirm']) }}
    </div>

    {{= $storeHelpers->hidden(form: $form, input: 'genderType') }}
    {{= $storeHelpers->hidden(form: $form, input: 'familyName') }}
    {{= $storeHelpers->hidden(form: $form, input: 'givenName') }}
    {{= $storeHelpers->hidden(form: $form, input: 'phoneticFamilyName') }}
    {{= $storeHelpers->hidden(form: $form, input: 'phoneticGivenName') }}
    {{= $storeHelpers->hidden(form: $form, input: 'phoneNumber') }}
    {{= $storeHelpers->hidden(form: $form, input: 'email') }}
    {{= $storeHelpers->hidden(form: $form, input: 'password') }}
    {{= $storeHelpers->hidden(form: $form, input: 'agree') }}
    {{= csrfTokenField($form) }}
</form>
{{ endBlock () }}
