@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <list-component :programs-prop='@json($programs)' :route="'{{ url('episodes') }}'"></list-component>
      </div>

      <div class="col-md-4">
        <form-component :programs='@json($programs)' :token="'{{ csrf_token() }}'" :route="'{{ route('episodes.store') }}'"></form-component>
      </div>
    </div>
  </div>
@endsection
