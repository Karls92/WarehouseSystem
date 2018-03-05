<!DOCTYPE html>
<html lang="es">
    <head>
        @include('backend.auth.html_header')
        @yield('css_header')
    </head>
    <body>
        @yield('content')

        <?=js_dir('jquery-1.11.3.min.js',true)."\n";?>
        <?=plugins_js_dir('bootstrap-3.3.7/js/bootstrap.min.js')."\n";?>
    </body>
</html>