@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <program-form-component 
        :is-edit="false"
        :data="null"
        :token="'{{ csrf_token() }}'" 
        :route="'{{ route('programs.store') }}'"
      >
      </program-form-component>
    </div>
  </div>
@endsection
