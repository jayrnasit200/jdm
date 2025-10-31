<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">{{ __('Edit Shop') }}</h2>
    </x-slot>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">

                        {{-- Validation Errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('shops.update', $shop->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Company Name --}}
                            <div class="mb-3">
                                <label class="form-label">Company Name</label>
                                <input type="text" name="company_name" class="form-control" 
                                       value="{{ old('company_name', $shop->company_name) }}" required>
                            </div>

                            {{-- Reference --}}
                            <div class="mb-3">
                                <label class="form-label">Reference (Ref)</label>
                                <input type="text" name="ref" class="form-control" 
                                       value="{{ old('ref', $shop->ref) }}">
                            </div>

                            {{-- Shop Name --}}
                            <div class="mb-3">
                                <label class="form-label">Shop Name</label>
                                <input type="text" name="shopname" class="form-control" 
                                       value="{{ old('shopname', $shop->shopname) }}" required>
                            </div>

                            {{-- Address --}}
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" required>{{ old('address', $shop->address) }}</textarea>
                            </div>

                            {{-- City --}}
                            <div class="mb-3">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" 
                                       value="{{ old('city', $shop->city) }}" required>
                            </div>

                            {{-- Postcode --}}
                            <div class="mb-3">
                                <label class="form-label">Postcode</label>
                                <input type="text" name="postcode" class="form-control" 
                                       value="{{ old('postcode', $shop->postcode) }}" required>
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" 
                                       value="{{ old('email', $shop->email) }}" required>
                            </div>

                            {{-- Phone --}}
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" 
                                       value="{{ old('phone', $shop->phone) }}" required>
                            </div>

                            {{-- VAT --}}
                            <div class="mb-3">
                                <label class="form-label">VAT</label>
                                <input type="text" name="Vat" class="form-control" 
                                       value="{{ old('Vat', $shop->Vat) }}">
                            </div>

                            {{-- Staff Name --}}
                            <div class="mb-3">
                                <label class="form-label">Staff Name</label>
                                <input type="text" name="Name_staff" class="form-control" 
                                       value="{{ old('Name_staff', $shop->Name_staff) }}">
                            </div>

                            {{-- Staff Number 1 --}}
                            <div class="mb-3">
                                <label class="form-label">Staff Number 1</label>
                                <input type="text" name="Staffnumber1" class="form-control" 
                                       value="{{ old('Staffnumber1', $shop->Staffnumber1) }}">
                            </div>

                            {{-- Staff Number 2 --}}
                            <div class="mb-3">
                                <label class="form-label">Staff Number 2</label>
                                <input type="text" name="Staffnumber2" class="form-control" 
                                       value="{{ old('Staffnumber2', $shop->Staffnumber2) }}">
                            </div>

                            {{-- Buttons --}}
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('shops.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update Shop</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
