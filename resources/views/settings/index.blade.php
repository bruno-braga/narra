@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <setting-form-component 
        :languages='@json($languages)'
        :is-edit="false"
        :data='@json($settings)'
        :token="'{{ csrf_token() }}'" 
        :route="'{{ route('storeSettings', $program->slug) }}'"
      >
      </setting-form-component>
    </div>
  </div>
@endsection

