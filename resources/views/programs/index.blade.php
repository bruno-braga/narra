@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            Programs <a href="{{ url('/programs/create') }}" class="btn btn-primary float-right">Add</a>
          </div>

          <div class="card-body">
            <program-list-component :route="'{{ url('programs') }}'" :programs='@json($programs)'></list-component>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

