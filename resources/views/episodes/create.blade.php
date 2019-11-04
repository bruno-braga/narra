@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            Create Episode
          </div>

          <div class="card-body">
            <form-component :token="'{{ csrf_token() }}'" :route="'{{ route('episodes.store') }}'"></form-component>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

