@extends('layouts.owner')

@section('title', 'Shops')
@section('page_title', 'Shops')
@section('page_subtitle', 'Choose which sellers can see each shop.')

@section('content')
<div class="container-fluid px-0">
    @if(session('success'))
        <div class="alert alert-success py-2 mb-3">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3 owner-toolbar">
        <h6 class="mb-0">All Shops</h6>
    </div>

    @if ($shops->isEmpty())
        <div class="card shadow-soft border-0">
            <div class="card-body text-center py-5 text-muted">
                <i class="fa fa-building fa-2x mb-2"></i>
                <h6 class="mt-2">No shops found</h6>
                <p class="small mb-0">Shops created by sellers will appear here.</p>
            </div>
        </div>
    @else
        <div class="d-md-none d-flex flex-column gap-2 mb-3">
            @foreach ($shops as $shop)
                <div class="card shadow-soft border-0">
                    <div class="card-body py-3">
                        <div class="fw-semibold">{{ $shop->shopname }}</div>
                        <div class="small text-muted">{{ $shop->company_name }} · Ref: {{ $shop->ref ?: '—' }}</div>
                        <div class="small text-muted mb-2">{{ $shop->city }} {{ $shop->postcode }}</div>
                        <div class="mb-3">
                            @forelse ($shop->sellers as $seller)
                                <span class="badge bg-dark bg-opacity-75 me-1 mb-1">{{ $seller->name }}</span>
                            @empty
                                <span class="text-muted small">No sellers assigned</span>
                            @endforelse
                        </div>
                        <button type="button"
                                class="btn btn-outline-secondary btn-sm w-100 btn-shop-access"
                                data-bs-toggle="modal"
                                data-bs-target="#shopAccessModal"
                                data-shop-name="{{ $shop->shopname }}"
                                data-action="{{ route('owner.shops.access.update', $shop) }}"
                                data-seller-ids="{{ $shop->sellers->pluck('id')->values()->toJson() }}">
                            <i class="fa fa-users me-1"></i> Change sellers
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card shadow-soft border-0 d-none d-md-block">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Shop</th>
                                <th>Company</th>
                                <th>Ref</th>
                                <th>City</th>
                                <th>Sellers who can see this shop</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shops as $shop)
                                <tr>
                                    <td class="fw-semibold">{{ $shop->shopname }}</td>
                                    <td>{{ $shop->company_name }}</td>
                                    <td>{{ $shop->ref ?: '—' }}</td>
                                    <td>{{ $shop->city }}</td>
                                    <td>
                                        @forelse ($shop->sellers as $seller)
                                            <span class="badge bg-dark bg-opacity-75 me-1 mb-1">{{ $seller->name }}</span>
                                        @empty
                                            <span class="text-muted small">None</span>
                                        @endforelse
                                    </td>
                                    <td class="text-end">
                                        <button type="button"
                                                class="btn btn-outline-secondary btn-sm btn-shop-access"
                                                data-bs-toggle="modal"
                                                data-bs-target="#shopAccessModal"
                                                data-shop-name="{{ $shop->shopname }}"
                                                data-action="{{ route('owner.shops.access.update', $shop) }}"
                                                data-seller-ids="{{ $shop->sellers->pluck('id')->values()->toJson() }}">
                                            <i class="fa fa-users me-1"></i> Change sellers
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="modal fade" id="shopAccessModal" tabindex="-1" aria-labelledby="shopAccessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-soft">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h6 class="modal-title" id="shopAccessModalLabel">Shop sellers</h6>
                    <small class="text-muted" id="shopAccessShopName">
                        Tick which sellers can see this shop.
                    </small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="shopAccessForm" method="POST" action="#">
                @csrf
                <div class="modal-body pt-2">
                    <div class="small text-muted mb-2">
                        Tick the sellers who should see this shop:
                    </div>

                    @if ($sellers->isEmpty())
                        <p class="small text-muted mb-0">No sellers found. Add a seller first.</p>
                    @else
                        <input type="search" id="shopSellerSearch" class="form-control form-control-sm mb-2" placeholder="Search sellers…">
                        <div class="card border-0 mb-0" style="max-height: 280px; overflow-y: auto;">
                            <div class="card-body py-2">
                                @foreach ($sellers as $seller)
                                    <div class="form-check mb-1 shop-seller-row" data-search="{{ strtolower($seller->name.' '.$seller->email) }}">
                                        <input class="form-check-input shop-access-check"
                                               type="checkbox"
                                               value="{{ $seller->id }}"
                                               id="shopSeller{{ $seller->id }}"
                                               name="seller_ids[]">
                                        <label class="form-label small mb-0" for="shopSeller{{ $seller->id }}">
                                            {{ $seller->name }}
                                            <span class="text-muted">· {{ $seller->email }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-dark btn-sm" @disabled($sellers->isEmpty())>
                        Save sellers
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
        $('.btn-shop-access').on('click', function () {
            const shopName = $(this).data('shop-name');
            const actionUrl = $(this).data('action');
            const sellerIds = $(this).data('seller-ids') || [];

            $('#shopAccessShopName').text('Who can see: ' + shopName);
            $('#shopAccessForm').attr('action', actionUrl);
            $('#shopSellerSearch').val('');
            $('.shop-seller-row').show();
            $('.shop-access-check').prop('checked', false);

            (Array.isArray(sellerIds) ? sellerIds : []).forEach(function (id) {
                $('#shopSeller' + id).prop('checked', true);
            });
        });

        $('#shopSellerSearch').on('input', function () {
            const q = $(this).val().toLowerCase();
            $('.shop-seller-row').each(function () {
                $(this).toggle(String($(this).data('search')).indexOf(q) !== -1);
            });
        });
    });
</script>
@endpush
