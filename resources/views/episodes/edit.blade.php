@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <episode-form-component 
        :is-edit="true"
        :data='@json($episode)'
        :programs='@json($programs)'
        :token="'{{ csrf_token() }}'" 
        :route="'{{ route('episodes.store') }}'"
      >
      </program-form-component>
    </div>
  </div>
@endsection
