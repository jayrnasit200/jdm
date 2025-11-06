    <x-app-layout>


        <div class="container my-5">
            {{-- ✅ Success Message --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="shopsTable" class="table table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>REF</th>
                                    <th>Shop Name</th>
                                    <th>Last Ordar</th>
                                    <th>Total Sale</th>

                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shops as $shop)
                                    <tr>
                                        <td>{{ $shop->id }}</td>
                                        <td>{{ $shop->ref }}</td>
                                        <td>{{ $shop->shopname }}</td>
                                        <td></td>
                                        <td></td>


                                        <td class="text-end">
                                            {{-- Edit Button --}}
                                            <a href="{{ route('shop.show', $shop->id) }}" class="btn btn-primary">
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
            <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
            <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

            <script>
              $(document).ready(function () {
    // Initialize DataTable only if it hasn't been initialized already
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

    // Auto-hide success alert
    const alert = document.querySelector('.alert-success');
    if (alert) {
        setTimeout(() => new bootstrap.Alert(alert).close(), 4000);
    }
});

            </script>
        @endpush
    </x-app-layout>
