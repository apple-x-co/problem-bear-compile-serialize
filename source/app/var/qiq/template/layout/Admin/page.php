{{ extends ('layout/Admin/base') }}

{{ setBlock ('body') }}
<header>
    <p>HEADER</p>
    {{ setBlock ('body_header') }}
    <form method="post" action="/admin/logout">
        <button>LOGOUT</button>
    </form>
    {{= getBlock () ~}}
</header>

<main>{{ setBlock ('body_content') }}{{= getBlock () ~}}</main>

<footer>
    <p>FOOTER</p>
    {{ setBlock ('body_footer') }}
    {{= getBlock () ~}}
</footer>
{{ endBlock () }}
