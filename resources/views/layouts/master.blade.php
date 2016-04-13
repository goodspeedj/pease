<!DOCTYPE html>
<html>
  <head>
    <title>@yield('title')</title>
    
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ URL::asset('css/sticky-footer-navbar.css') }}">
    @yield('custom_css')
  </head>

  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.cfm">Pease Well Water</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="/">Home</a></li>
            <li class="dropdown">
              <a href="/wellsample/" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Well Samples - Tables<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="/wellsample/haven">By Well</a></li>
                <li><a href="/wellsample/pfoa">By PFC</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="/wellsample/haven/chart" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Well Samples - Charts<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="/wellsample/haven/chart">By Well</a></li>
                <li><a href="/wellsample/pfoa/chart">By PFC</a></li>
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
      <div class="row">
        <div class="col-md-12">
          @yield('content')
        </div>
      </div>

    </div>

    <footer class="footer">
      <div class="container">
        <p class="text-muted">Place sticky footer content here.</p>
      </div>
    </footer>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    @yield('custom_js')
  </body>
</html>