<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>WildCode</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600|Monoton" rel="stylesheet" type="text/css">
        <link href="{{asset('css/button.min.css')}}" rel="stylesheet">
        <!-- Styles -->
        <style>
            html, body {
                background-color: #2c185b;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .content {
                text-align: center;
            }

            .title {
                color: #ff5555;
                font-family: 'Monoton', sans-serif;
                font-weight: 100;
                font-size: 120px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    WILDCODE
                </div>
                <div>
                    <a class="ui inverted button" href="{{ url('/login') }}">Login</a>
                    <a class="ui inverted button" href="{{ url('/register') }}">Register</a>
                </div>
            </div>
        </div>
    </body>
</html>
