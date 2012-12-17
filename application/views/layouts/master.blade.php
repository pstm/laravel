<!DOCTYPE html>
<html lang="{{ Muli::get_lang() }}">
    <head>
        <meta charset="utf-8">
        {{ HTML::style('css/normalize.css') }}
        {{ HTML::style('css/app.css') }}
        <title>{{ Muli::get_page_title() }} |  {{ __('title.sitename') }}</title>
    </head>
    <body>
        <header>
            <div class="wrapper">
                <a class="btn-home" href="{{ URL::to_route('home') }}">Logo</a>
                <nav>
                    {{ Menu::generate_menu('secondary', FALSE, 0, 'utils') }}
                </nav>
            </div>
        </header>
        <div class="main">
            <div class="wrapper">
                <h1>{{ Muli::get_page_title(true) }}</h1>
                @yield('content')

                <div style="overflow:hidden">
                {{ Menu::generate_menu('type', FALSE, 0, 'main') }}
                </div>

                {{ Menu::generate_sidebar_menu('type', FALSE, 4, 'sub') }}

            </div>
        </div>
        <footer>
            <div class="wrapper">

            </div>
        </footer>

        {{ HTML::script('js/app.js') }}
    </body>
</html>