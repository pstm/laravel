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
                <nav>
                    {{ Menu::generate_menu('secondary', FALSE, 0, 'utils') }}
                </nav>
                <a class="btn-home" href="{{ URL::to_route('home') }}">Logo</a>
                <nav>
                    {{ Menu::generate_menu('type', FALSE, 0, 'primary') }}
                </nav>
            </div>
        </header>
        <div class="main">
            <div class="wrapper">
                <div class="content full">
                    <h1>{{ Muli::get_page_title(true) }}</h1>
                    @yield('content')
                </div>
            </div>
        </div>
        <footer>
            <div class="wrapper">

            </div>
        </footer>

        {{ HTML::script('js/app.js') }}
    </body>
</html>