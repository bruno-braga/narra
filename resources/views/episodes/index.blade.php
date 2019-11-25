@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div>
          <a href="{{ url('/episodes/create') }}" class="btn btn-primary float-right">Add Episode</a>
        </div>

        <episode-list-component 
          :programs-prop='@json($programs)'
          :route="'{{ url('episodes') }}'">
        </list-component>
      </div>
    </div>
  </div>
@endsection
