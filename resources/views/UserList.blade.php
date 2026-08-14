@extends('layouts.app')

@section('title')

@section('content')
<div class="row g-3 mb-3">
  @if (session('message'))
  <div id="alert" class="alert alert-success" >{{ session('message') }}</div>
@endif
    <div class="col-md-12 col-xxl-3">
      <div class="card h-md-100 ecommerce-card-min-width">
        <div class="card-header pb-0">
          <h6 class="mb-0 mt-2 d-flex align-items-center">Users</h6>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
          {{-- <a href="{{url('/newecatego')}}ry" class="btn btn-dark">New Category</a> --}}
        </div>

        </div>
        <div class="card-body d-flex flex-column justify-content-end">
          
          <div class="row">

            <div id="tableExample2" data-list='{"valueNames":["name","email","age"],"page":5,"pagination":true}'>
              <table class="table table-bordered table-striped fs--1">
                <thead class="bg-200 text-900">
                  <tr>
                    <th class="sort" data-sort="name">Name</th>
                    <th class="sort" data-sort="age">admin</th>
                    <th class="sort" data-sort="age">Admin</th>

                  </tr>
                </thead>
                <tbody class="list">
                  @foreach($users as $y)
                  <tr>
                    <td class="name">{{$y->name}}</td>
                    <td class="admin">{{$y->is_admin}}</td>
                   
                    {{-- <td class="age"><a href="{{ url('/userview',$y->id) }}" class="btn btn-primary"><i class="far fa-clone"></i></a></td> --}}
                    <td class="age"><a href="{{ url('/useradmin',$y->id) }}" class="btn btn-success"><i class="fa fa-check"></i></a></td>
                  </tr>
                        
                    @endforeach
                 
                 
                </tbody>
              </table>
              <div class="d-flex justify-content-center"><button class="btn btn-sm btn-falcon-default me-1" type="button" title="Previous" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
                <ul class="pagination mb-0"></ul><button class="btn btn-sm btn-falcon-default ms-1" type="button" title="Next" data-list-pagination="next"><span class="fas fa-chevron-right"> </span></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  
  
  
  </div>



  @endsection
  @section('js')
<script>
  setTimeout(function() {
    $('#alert').fadeOut('fast');
}, 3000);
</script>
  @endsection