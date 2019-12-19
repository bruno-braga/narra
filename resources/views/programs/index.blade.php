@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div>
          <a href="{{ url('/programs/create') }}" class="btn btn-primary float-right">Add Program</a>
          <a href="{{ url('/episodes/create') }}" class="btn btn-primary float-right" style="margin-right: 10px">Add Episode</a>
        </div>

            <program-list-component :route="'{{ url('programs') }}'" :programs='@json($programs)'></list-component>
      </div>
    </div>
  </div>
@endsection

