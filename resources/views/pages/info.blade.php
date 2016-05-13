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
      <p>PFCs are a group of manufactured checmicals used in many every day products to make the more resistant to stains,
         heat and water.  PFCs are also used in fire fighting foam.  These chemicals break down very slowly in the environment
         and their concentration bioaccumulates in the body over time with continued exposure.  PFCs have been linked to low birth
         weight, elevated cholesterol and problems with the immune system, liver and endocrine systems.</p>
      <p><a class="btn btn-primary" href="#" role="button">Learn More &raquo;</a></p>
   </div>
    <div class="col-lg-4">
      <h3>About Pease Contamination</h3>
      <p>In April 2014 a test for PFCs on the Pease Tradeport came back with elevated levels of PFCs from Haven well.  A subsequent test
         in May 2014 also showed levels over twelve times the EPAs public health advisory - at that time the City of Portsmouth closed the well.  
         The likely cause of the PFC is fire fighting foam used on the former Air Force base in the 1990s.  While other wells on and around Pease
         are below the EPAs provisional health advisory they all have PFCs present.</p>
      <p><a class="btn btn-primary" href="#" role="button">Learn More &raquo;</a></p>
    </div>
  </div>

@stop