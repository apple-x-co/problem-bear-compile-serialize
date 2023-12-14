<!doctype html>
<html lang="ja">
<head>
    <title>{{ setBlock ('title') }}BEAR.Sunday{{= getBlock () ~}}</title>
    <meta charset="UTF-8">
    {{ setBlock ('head_meta') }}{{= getBlock () ~}}
    {{ setBlock ('head_styles') }}{{= getBlock () ~}}
    {{ setBlock ('head_scripts') }}
</head>
<body>
{{= getContent() }}
</body>
</html>
