<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{config("app.name",'LSAPP DEFAULT')}}</title>
        <link rel="stylesheet" href={{asset('css/app.css')}}>
        <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    </head>
    <body>
        @include('inc.navbar')
        <div class="container p-3">
            @include('inc.messages')
            @yield('content')
        </div>
        <script>
            CKEDITOR.replace( 'editor1' );
        </script>
    </body>
</html>
