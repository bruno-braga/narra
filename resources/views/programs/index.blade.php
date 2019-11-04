@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            Programs
          </div>

          <div class="card-body">
            <program-list-component :route="'{{ url('programs') }}'" :programs='@json($programs)'></list-component>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <program-form-component 
          :token="'{{ csrf_token() }}'" 
          :route="'{{ route('programs.store') }}'"
        >
        </program-form-component>
      </div>
    </div>
  </div>
@endsection

