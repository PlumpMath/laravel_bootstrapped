<!DOCTYPE html>
    <!--[if IE 7 ]>    <html class="no-js ie7"> <![endif]-->
    <!--[if (gt IE 7)|!(IE)]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <title></title>
        <meta name="description" content="">
        <meta name="HandheldFriendly" content="True">
        <meta name="MobileOptimized" content="320">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="cleartype" content="on">
        @foreach ($asset['css'] as $css)
            {{ HTML::style($css) }}
        @endforeach
    </head>
    <body>
        @yield('content')
        
        @foreach ($asset['js'] as $js)
            {{ HTML::script($js) }}
        @endforeach
    </body>
</html>
