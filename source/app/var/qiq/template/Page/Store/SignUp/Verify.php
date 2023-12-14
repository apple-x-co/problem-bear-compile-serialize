{{ extends ('layout/Store/public-page') }}

{{ setBlock ('title') }}アカウント認証 | 新規会員登録 | {{ parentBlock () }}{{ endBlock () }}

{{ setBlock ('head_scripts') }}
{{ parentBlock () }}
<script>
    document.addEventListener('DOMContentLoaded', function () {
      document.forms['verify'].submit();
    });
</script>
{{ endBlock () }}

{{ setBlock ('body_content') }}
<p>そのままお待ちください...</p>
<form name="verify" method="post">
    <input type="hidden" name="signature" value="{{= $signature }}">
</form>
{{ endBlock () }}
