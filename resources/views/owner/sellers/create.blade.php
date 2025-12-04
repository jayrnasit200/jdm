@extends('layouts.owner')

@section('title', 'Add Seller')
@section('page_title', 'Add Seller')
@section('page_subtitle', 'Create a new seller account for your team.')

@section('content')
    <div class="container-fluid px-0">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-soft border-0">
                    <div class="card-body">
                        <h6 class="mb-3">Seller details</h6>

                        <form method="POST" action="{{ route('owner.sellers.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label small">Name</label>
                                <input type="text" name="name"
                                       class="form-control form-control-sm @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" required>
                                @error('name')
                                <div class="invalid-feedback small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label small">Email</label>
                                <input type="email" name="email"
                                       class="form-control form-control-sm @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" required>
                                @error('email')
                                <div class="invalid-feedback small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label small">Password</label>
                                <input type="password" name="password"
                                       class="form-control form-control-sm @error('password') is-invalid @enderror"
                                       required>
                                @error('password')
                                <div class="invalid-feedback small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button class="btn btn-dark btn-sm">
                                    Save Seller
                                </button>
                                <a href="{{ route('owner.sellers.index') }}"
                                   class="btn btn-outline-secondary btn-sm">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
