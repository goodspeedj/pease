@extends('layouts.master')

@section('title', 'Participants')

@section('custom_css')
    @include('partials.datatablesCSS')
@stop

@section('content')
    <h3>Blood Sample Participants</h3>

    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <th>Participant ID</th>
        <th>PFOA</th>
        <th>PFOS</th>
        <th>PFHxS</th>
        <th>PFOSA</th>
        <th>PFNA</th>
        <th>PFPeA</th>
        <th>PFHxA</th>
        <th>PFBA</th>
      </thead>
      <tbody>
        @foreach ($wellSamples as $wellSample)
          <tr>
            <td>{{ $wellSample->nhHHSID }}</td>
            <td>{{ $wellSample->PFOA }} <sub>{{ $wellSample->PFOANote }}</sub></td>
            <td>{{ $wellSample->PFOS }} <sub>{{ $wellSample->PFOSNote }}</sub></td>
            <td>{{ $wellSample->PFHxS }} <sub>{{ $wellSample->PFHxSNote }}</sub></td>
            <td>{{ $wellSample->PFOSA }} <sub>{{ $wellSample->PFOSANote }}</sub></td>
            <td>{{ $wellSample->PFNA }} <sub>{{ $wellSample->PFNANote }}</sub></td>
            <td>{{ $wellSample->PFPeA }} <sub>{{ $wellSample->PFPeANote }}</sub></td>
            <td>{{ $wellSample->PFHxA }} <sub>{{ $wellSample->PFHxANote }}</sub></td>
            <td>{{ $wellSample->PFBA }} <sub>{{ $wellSample->PFBANote }}</sub></td>
          </tr>
        @endforeach
      </tbody>
    </table>
@stop

@section('custom_js')
    @include('partials.datatablesJS')
@stop