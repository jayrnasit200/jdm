@extends('layouts.owner')

@section('title', 'Sellers')
@section('page_title', 'Sellers')
@section('page_subtitle', 'Manage seller accounts and their permissions.')

@section('content')
    <div class="container-fluid px-0">
        @if(session('success'))
            <div class="alert alert-success py-2 mb-3">{{ session('success') }}</div>
        @endif>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0">All Sellers</h6>
            <a href="{{ route('owner.sellers.create') }}" class="btn btn-sm btn-dark">
                <i class="fa fa-plus me-1"></i> Add Seller
            </a>
        </div>

        <div class="card shadow-soft border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0 align-middle">
                        <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Shop</th>
                            <th>Products</th>
                            <th>Categories</th>
                            <th>Discounts</th>
                            <th class="text-end">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($sellers as $seller)
                        @php
                        $perm = $seller->permission;
                    @endphp
                    <tr>
                        <td>{{ $seller->name }}</td>
                        <td>{{ $seller->email }}</td>

                        <td>
                            @if($perm?->shop)
                                <span class="badge bg-success bg-opacity-75">Yes</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-50">No</span>
                            @endif
                        </td>

                        <td>
                            @if($perm?->products)
                                <span class="badge bg-success bg-opacity-75">Yes</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-50">No</span>
                            @endif
                        </td>

                        <td>
                            @if($perm?->categories)
                                <span class="badge bg-success bg-opacity-75">Yes</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-50">No</span>
                            @endif
                        </td>

                        <td>
                            @if($perm?->discounts)
                                <span class="badge bg-success bg-opacity-75">Yes</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-50">No</span>
                            @endif
                        </td>

                        <td class="text-end">
                            <button type="button"
                                    class="btn btn-outline-secondary btn-sm btn-permissions"
                                    data-bs-toggle="modal"
                                    data-bs-target="#permissionsModal"
                                    data-seller-id="{{ $seller->id }}"
                                    data-seller-name="{{ $seller->name }}"
                                    data-action="{{ route('owner.sellers.permissions.update', $seller->id) }}"
                                    data-perm-shop="{{ $perm?->shop ? 1 : 0 }}"
                                    data-perm-products="{{ $perm?->products ? 1 : 0 }}"
                                    data-perm-categories="{{ $perm?->categories ? 1 : 0 }}"
                                    data-perm-discounts="{{ $perm?->discounts ? 1 : 0 }}">
                                <i class="fa fa-lock me-1"></i> Permissions
                            </button>
                        </td>
                    </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-3">
                                    No sellers found.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- 🔐 Permissions Modal --}}
    <div class="modal fade" id="permissionsModal" tabindex="-1" aria-labelledby="permissionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-soft">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h6 class="modal-title" id="permissionsModalLabel">Seller Permissions</h6>
                        <small class="text-muted" id="permissionsSellerName">
                            Set which modules this seller can access.
                        </small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="permissionsForm" method="POST" action="#">
                    @csrf
                    <input type="hidden" name="seller_id" id="permissionsSellerId">

                    <div class="modal-body pt-2">
                        <div class="small text-muted mb-2">
                            Tick the areas this seller should be able to use:
                        </div>

                        <div class="card border-0 mb-2">
                            <div class="card-body py-2">
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" value="1" id="permShop" name="permissions[shop]">
                                    <label class="form-label small" for="permShop">Shop</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" value="1" id="permProducts" name="permissions[products]">
                                    <label class="form-label small" for="permProducts">Products</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" value="1" id="permCategories" name="permissions[categories]">
                                    <label class="form-label small" for="permCategories">Categories</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" value="1" id="permDiscounts" name="permissions[discounts]">
                                    <label class="form-label small" for="permDiscounts">Discounts</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-dark btn-sm">
                            Save Permissions
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $(function () {
        $('.btn-permissions').on('click', function () {
            const sellerId   = $(this).data('seller-id');
            const sellerName = $(this).data('seller-name');
            const actionUrl  = $(this).data('action');

            const pShop       = Number($(this).data('perm-shop')) === 1;
            const pProducts   = Number($(this).data('perm-products')) === 1;
            const pCategories = Number($(this).data('perm-categories')) === 1;
            const pDiscounts  = Number($(this).data('perm-discounts')) === 1;

            $('#permissionsSellerName').text('Set module access for: ' + sellerName);
            $('#permissionsSellerId').val(sellerId);
            $('#permissionsForm').attr('action', actionUrl);

            $('#permShop').prop('checked', pShop);
            $('#permProducts').prop('checked', pProducts);
            $('#permCategories').prop('checked', pCategories);
            $('#permDiscounts').prop('checked', pDiscounts);
        });
    });
</script>
@endpush

