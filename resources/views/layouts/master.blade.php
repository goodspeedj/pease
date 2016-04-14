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
              <a href="/wellsample/haven" role="button" aria-haspopup="true" aria-expanded="false">Well Samples - Tables</a>
            </li>
            <li class="dropdown">
              <a href="/wellsample/well/haven/chart" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Samples Charts - by Well<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="/wellsample/well/haven/chart">Haven</a></li>
                <li><a href="/wellsample/well/smith/chart">Smith</a></li>
                <li><a href="/wellsample/well/harrison/chart">Harrison</a></li>
                <li><a href="/wellsample/well/collins/chart">Collins</a></li>
                <li><a href="/wellsample/well/portsmouth/chart">Portsmouth</a></li>
                <li><a href="/wellsample/well/wwtp/chart">WWTP</a></li>
                <li><a href="/wellsample/well/des/chart">DES</a></li>
                <li><a href="/wellsample/well/gbk_pre/chart">Great Bay Kids - Pre Treatment</a></li>
                <li><a href="/wellsample/well/gbk_post1/chart">Great Bay Kids - Post Treatment #1</a></li>
                <li><a href="/wellsample/well/gbk_post2/chart">Great Bay Kids - Post Treatment #2</a></li>
                <li><a href="/wellsample/well/dsc_pre/chart">Discovery Child Enrichment Pre Treatment</a></li>
                <li><a href="/wellsample/well/dsc_post/chart">Discovery Child Enrichment Post Treatment</a></li>
                <li><a href="/wellsample/well/firestation/chart">Firestation</a></li>
                <li><a href="/wellsample/well/csw-1d/chart">CSW-1D Sentry Well</a></li>
                <li><a href="/wellsample/well/csw-1s/chart">CSW-1S Sentry Well</a></li>
                <li><a href="/wellsample/well/csw-2r/chart">CSW-2R Sentry Well</a></li>
                <li><a href="/wellsample/well/hmw-3/chart">HMW-3 Sentry Well</a></li>
                <li><a href="/wellsample/well/hmw-8r/chart">HMW-8R Sentry Well</a></li>
                <li><a href="/wellsample/well/hmw-14/chart">HMW-14 Sentry Well</a></li>
                <li><a href="/wellsample/well/hmw-15/chart">HMW-15 Sentry Well</a></li>
                <li><a href="/wellsample/well/smw-a/chart">SMW-A Sentry Well</a></li>
                <li><a href="/wellsample/well/smw-13/chart">SMW-13 Sentry Well</a></li>
                <li><a href="/wellsample/well/psw-1/chart">PSW-1 Sentry Well</a></li>
                <li><a href="/wellsample/well/psw-2/chart">PSW-2 Sentry Well</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="/wellsample/pfc/pfos/chart" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Samples Charts - by PFC<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="/wellsample/pfc/pfoa/chart">PFOA</a></li>
                <li><a href="/wellsample/pfc/pfos/chart">PFOS</a></li>
                <li><a href="/wellsample/pfc/pfhxs/chart">PFHxS</a></li>
                <li><a href="/wellsample/pfc/pfhxa/chart">PFHxA</a></li>
                <li><a href="/wellsample/pfc/pfosa/chart">PFOSA</a></li>
                <li><a href="/wellsample/pfc/pfba/chart">PFBA</a></li>
                <li><a href="/wellsample/pfc/pfbs/chart">PFBS</a></li>
                <li><a href="/wellsample/pfc/pfda/chart">PFDA</a></li>
                <li><a href="/wellsample/pfc/pfds/chart">PFDS</a></li>
                <li><a href="/wellsample/pfc/pfdoa/chart">PFDoA</a></li>
                <li><a href="/wellsample/pfc/pfhps/chart">PFHpS</a></li>
                <li><a href="/wellsample/pfc/pfhpa/chart">PFHpA</a></li>
                <li><a href="/wellsample/pfc/pfna/chart">PFNA</a></li>
                <li><a href="/wellsample/pfc/pfpea/chart">PFPeA</a></li>
                <li><a href="/wellsample/pfc/pfteda/chart">PFTeDA</a></li>
                <li><a href="/wellsample/pfc/pftrda/chart">PFTrDA</a></li>
                <li><a href="/wellsample/pfc/pfuna/chart">PFUnA</a></li>
                <li><a href="/wellsample/pfc/6:2 FTS/chart">6:2 FTS</a></li>
                <li><a href="/wellsample/pfc/8:2 FTS/chart">8:2 FTS</a></li>
                <li><a href="/wellsample/pfc/etfosa/chart">EtFOSA</a></li>
                <li><a href="/wellsample/pfc/etfose/chart">EtFOSE</a></li>
                <li><a href="/wellsample/pfc/mefosa/chart">MeFOSA</a></li>
                <li><a href="/wellsample/pfc/mefose/chart">MeFOSE</a></li>
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