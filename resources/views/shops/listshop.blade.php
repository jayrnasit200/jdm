<x-app-layout>

    {{-- 🎨 Custom Page Styling --}}
    <style>
        body {
            background-color: #f3f4f6;
        }

        .page-section {
            padding-top: 40px;
            padding-bottom: 40px;
        }

        .shop-card {
            border-radius: 1rem;
            border: 0;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-header-modern {
            background:
        linear-gradient(135deg, rgba(11,60,93,0.90), rgba(30,64,175,0.90)),
        url('https://upload.wikimedia.org/wikipedia/commons/a/a4/Flag_of_the_United_States.svg');
    background-size: cover;
    background-position: center right;
    color: #ffffff;
    border-top-left-radius: 1rem;
    border-top-right-radius: 1rem;
            color: #ffffff;
            padding: 20px 25px;
        }

        .table-modern thead {
            background: #e9efff;
            color: #1e293b;
            font-weight: 600;
        }

        .table-hover tbody tr:hover {
            background: #eef2ff !important;
        }

        .btn-view {
            background-color: #1d4ed8 !important;
            border: none;
            border-radius: 999px;
            padding: 6px 14px;
        }

        .btn-view:hover {
            background-color: #1e40af !important;
        }

        .alert-success {
            border-left: 5px solid #22c55e;
            font-weight: 500;
        }

        .title-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .title-bar h2 {
            font-weight: 700;
            color: #0f172a;
        }

        .dataTables_filter input {
            border-radius: 999px !important;
            padding: 6px 15px !important;
            border: 1px solid #d1d5db !important;
        }
    </style>

    <div class="container page-section">

        {{-- ✅ Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="title-bar">
            <h2>Shops Overview</h2>
        </div>

        <div class="card shop-card">
            <div class="card-header-modern">
                <h4 class="mb-0">All Shops</h4>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="shopsTable" class="table table-modern table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>REF</th>
                                <th>Shop Name</th>
                                <th>Last Order</th>
                                <th>Total Sale</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shops as $shop)
                                <tr>
                                    <td>{{ $shop->id }}</td>
                                    <td>{{ $shop->ref }}</td>
                                    <td>{{ $shop->shopname }}</td>
                                    <td>
                                        {{-- Last Order Date (if available) --}}
                                        @php
                                            $lastOrder = $shop->orders->sortByDesc('created_at')->first();
                                        @endphp
                                        {{ $lastOrder ? $lastOrder->created_at->format('d M Y') : '—' }}
                                    </td>

                                    <td>
                                        {{-- Total Sales --}}
                                        £{{ number_format($shop->orders->sum('total'), 2) }}
                                    </td>

                                    <td class="text-end">
                                        <a href="{{ route('shop.show', $shop->id) }}" class="btn btn-view text-white">
                                            <i class='fa fa-arrow-right'></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>



    {{-- Include DataTables CDN --}}
    @push('scripts')
        {{-- Datatables Style + Scripts --}}
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

        <script>
            $(document).ready(function () {

                // Initialise DataTable once
                if (!$.fn.DataTable.isDataTable('#shopsTable')) {
                    $('#shopsTable').DataTable({
                        pageLength: 10,
                        order: [[0, 'desc']],
                        language: {
                            search: "_INPUT_",
                            searchPlaceholder: "Search shops..."
                        }
                    });
                }

                // Auto-close success alerts
                const alert = document.querySelector('.alert-success');
                if (alert) {
                    setTimeout(() => new bootstrap.Alert(alert).close(), 3500);
                }
            });
        </script>
    @endpush

</x-app-layout>
