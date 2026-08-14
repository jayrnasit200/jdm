@extends('layouts.app')

@section('title')

@section('content')
<div class="card mb-3">
  <div class="card-header">
    <div class="row flex-between-end">
      <div class="col-auto col-lg align-self-center">
        <h5 class="mb-0"  >Category</h5>
      </div>
     
    </div>
  </div>
  <div class="card-body bg-light">
    <form action="{{ url('storecategory') }}" method="POST">
      @csrf
    <div class="tab-content">
      <div class="tab-pane preview-tab-pane active" role="tabpanel" aria-labelledby="tab-dom-f08c45b6-dcfd-4142-bec6-924bd7e953de" id="dom-f08c45b6-dcfd-4142-bec6-924bd7e953de">
        <div class="mb-3"><label class="form-label" for="name">Name</label><input class="form-control" id="category" name="name"  placeholder="Category">        @if($errors->has('name'))
          <div class="text-danger">{{ $errors->first('name') }}</div>
      @endif</div>

      </div>
   <input type="submit" class="btn btn-primary" value="Add Category">
   </form>
    </div>
  </div>
</div>



@endsection
@section('js')

@endsection