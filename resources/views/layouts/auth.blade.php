<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">

      <title>Encuestas</title>

       <link rel="stylesheet" href="/css/style-login.css">
    </head>

    
    <body>
        @yield('content')
    </body>



{{-- <script src='http://cdnjs.cloudflare.com/ajax/libs/gsap/1.16.1/TweenMax.min.js'></script> --}}
<script src="/libs/min/jquery-3.1.1.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $(document).ready(function () {
        $('#login-button').fadeIn(1000);
    });



    $('#login-button').click(function(){
        $("#login-button").fadeOut(500,function(){
            $(location).attr('href', '/login')
        });
    });

    $(".close-btn").click(function(){
        // TweenMax.from("#container", .4, { scale: 1, ease:Sine.easeInOut});
        // TweenMax.to("#container", .4, { left:"0px", scale: 0, ease:Sine.easeInOut});
        $("#container, #forgotten-container").fadeOut(800, function(){
          $(location).attr('href', '/')
        });
    });

    
});
</script>
    
</html>
