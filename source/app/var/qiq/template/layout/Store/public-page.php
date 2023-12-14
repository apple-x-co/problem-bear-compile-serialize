{{ extends ('layout/Store/base') }}

{{ setBlock ('body') }}
<header>
    <p>HEADER</p>
    {{ setBlock ('body_header') }}
    {{= getBlock () ~}}
</header>

<main>{{ setBlock ('body_content') }}{{= getBlock () ~}}</main>

<footer>
    <p>FOOTER</p>
    {{ setBlock ('body_footer') }}
    {{= getBlock () ~}}
</footer>
{{ endBlock () }}
