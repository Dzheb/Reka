@section('aside')
<div class="aside">
    @auth
    <p>Проект - {{ config('app.name', 'Laravel') }}</p>
    <h4>Разработчик:</h4>
    <h4>Джериев Б.М.</h4>
    <img style="border-radius:8px;" src="{{ asset('author.jpg') }}" width="100vh" height="content" vspace="10vh" alt="Автор">
    @show
    @endauth
</div>
