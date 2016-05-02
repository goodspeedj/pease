@extends('layouts.master')

@section('title', 'Info')

@section('content')
  <!-- Jumbotron -->
  <div class="jumbotron">
    <h2>Information</h2>
    <p class="lead">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet.</p>
  </div>

  <!-- Example row of columns -->
  <div class="row">
    <div class="col-lg-4">
      <h3>About this site</h3>
      <p>This site takes the publicly available water sample data from the Pease wells and distribution points and puts it into easy to understand tables and charts.</p>
      <p><a class="btn btn-primary" href="#" role="button">View Data &raquo;</a></p>
    </div>
    <div class="col-lg-4">
      <h3>About PFCs</h3>
      <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
      <p><a class="btn btn-primary" href="#" role="button">Learn More &raquo;</a></p>
   </div>
    <div class="col-lg-4">
      <h3>About Pease Contamination</h3>
      <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa.</p>
      <p><a class="btn btn-primary" href="#" role="button">Learn More &raquo;</a></p>
    </div>
  </div>

@stop