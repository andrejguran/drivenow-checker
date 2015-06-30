<html>
    <head>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="/styles/styles.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

        <title>Drive now Checker</title>
    </head>
    <body>

        <nav class="navbar">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar">asd</span>
                <span class="icon-bar">qwe</span>
                <span class="icon-bar">sdf</span>
              </button>
              <a class="navbar-brand" href="/">Drive now checker</a>
            </div>
            <ul class="nav nav-pills pull-right">
            <?php $action = Route::getCurrentRoute()->getName() ?>
                <li role="presentation" class="@if ($action == 'home') active @endif"><a href="/home">Home</a></li>
                <li role="presentation" class="@if ($action == 'settings') active @endif"><a href="/settings">Settings</a></li>
                <li role="presentation" class="@if ($action == 'login' || $action == 'register') active @endif">
                    @if ( Auth::check() )
                        <a href="/auth/logout">Logout</a>
                    @else
                        <a href="/auth/login">Login</a>
                    @endif
                </li>
              </ul>
          </div>
        </nav>


        <div class="container">

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (Session::has('info'))
                <div class="alert alert-info">
                    <ul>
                        @foreach (Session::get('info') as $info)
                            <li>{{ $info }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
          <hr>

          <footer>
            <p>© <a href="https://github.com/andrejguran/drivenow-checker">andrejguran</a></p>
          </footer>
        </div> <!-- /container -->


        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="../../dist/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>


    </body>