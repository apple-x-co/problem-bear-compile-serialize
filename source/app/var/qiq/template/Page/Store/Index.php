{{ extends ('layout/Store/page') }}
{{ $storeHelpers = storeHelpers() }}

{{ setBlock ('title') }}INDEX | {{ parentBlock () }}{{ endBlock () }}

{{ setBlock ('body_content') }}
INDEX
{{ endBlock () }}
