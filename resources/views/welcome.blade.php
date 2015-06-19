<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 96px;
                margin-bottom: 40px;
            }

            .quote {
                font-size: 24px;
            }

            a {
                font-weight: bold;
                text-decoration: none;
            }

            a:visited {
                color: #B0BEC5;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Drive now checker</div>
                <div class="quote">
                    Please <a href="{{ action('Auth\AuthController@getLogin') }}">Log in</a>
                    or <a href="{{ action('Auth\AuthController@getRegister') }}">Register</a>
                </div>
            </div>
        </div>
    </body>
</html>
