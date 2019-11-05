@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <program-form-component 
        :is-edit="true"
        :data='@json($program)'
        :token="'{{ csrf_token() }}'" 
        :route="'{{ route('programs.store') }}'"
      >
      </program-form-component>
    </div>
  </div>
@endsection