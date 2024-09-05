@extends('layout.app')

@section('title', 'Detail Product Entry')

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Detail Product Entry</h4>

                            <!-- Tampilkan data product entry -->
                            <div id="productEntryDetails">
                                <p><strong>ID Product Entry:</strong> <span id="entryId"></span></p>
                                <p><strong>Total:</strong> <span id="entryTotal"></span></p>
                            </div>
                            <br>
                            <div class="align-right text-right">
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#importModal">
                                    <i class="fas fa-file-excel"></i> Import Excel
                                </button>
                                <button type="button" class="btn btn-info"
                                    onclick="window.location.href='{{ route('product-entry.export', $productEntryId) }}'">
                                    <i class="fas fa-download"></i> Export Excel
                                </button>
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#createDetailModal">
                                    Tambah Detail
                                </button>
                            </div>
                            <br>

                            <div class="search-element">
                                <input id="searchInput" class="form-control" type="search" placeholder="Search"
                                    aria-label="Search">
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table id="detailsTable" class="table table-bordered zero-configuration">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Product ID</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-center">Price</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data akan dimuat di sini melalui AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tambah Detail Modal -->
        <div class="modal fade" id="createDetailModal" tabindex="-1" role="dialog"
            aria-labelledby="createDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createDetailModalLabel">Tambah Detail Product Entry</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="createDetailForm">
                        @csrf
                        <input type="hidden" name="product_entry_id" value="{{ $productEntryId }}">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="product_id">Product:</label>
                                <select class="form-control select2" id="product_id" name="product_id" required>
                                    <option value="">Select a product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="quantity">Quantity:</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" required>
                            </div>
                            <div class="form-group">
                                <label for="price">Price (Rupiah):</label>
                                <input type="text" class="form-control" id="price" name="price" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Import Modal -->
        <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Product Entry Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('product-entry.import', $productEntryId) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="file">Pilih File Excel:</label>
                                <input type="file" class="form-control" id="file" name="file" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                const productEntryId = @json($productEntryId);
                const products = @json($products);

                // Initialize Select2
                $('.select2').select2({
                    theme: 'bootstrap4',
                    placeholder: 'Select a product',
                    allowClear: true
                });

                // Load data initially
                loadData();

                let dataLoadInterval;

                // Function to start the interval
                function startDataLoadInterval() {
                    dataLoadInterval = setInterval(loadData, 2000);
                }

                // Start the interval initially
                startDataLoadInterval();

                function loadData() {
                    $.get(`/product-entry-details/load-data/${productEntryId}`, function(data) {
                        // Display product entry data
                        $('#entryId').text(data.productEntry.id);
                        $('#entryTotal').text(formatRupiah(data.productEntry.total));

                        // Load detail data into the table
                        let detailsHtml = '';
                        data.details.forEach((detail, index) => {
                            detailsHtml += `
                                <tr>
                                    <td class="text-center">${index + 1}</td>
                                    <td class="text-center">${detail.product.name}</td>
                                    <td class="text-center">${detail.quantity}</td>
                                    <td class="text-center">${formatRupiah(detail.price)}</td>
                                    <td class="text-center">${formatRupiah(detail.total)}</td>
                                    <td class="text-center">
                                        <button class="btn btn-danger btn-sm deleteDetailBtn" data-id="${detail.id}">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                        $('#detailsTable tbody').html(detailsHtml);
                    });
                }

                // Format number as Rupiah
                function formatRupiah(value) {
                    return "Rp " + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }

                // Handle form submission for adding detail
                $('#createDetailForm').on('submit', function(e) {
                    e.preventDefault();

                    const formData = $(this).serialize();
                    const priceString = $('#price').val().replace(/\./g, '').replace('Rp ',
                        ''); // Clean and convert price
                    const price = parseInt(priceString); // Convert to integer

                    $.post('/product-entry-details', formData + '&price=' + price, function(data) {
                        Swal.fire('Berhasil', data.message, 'success');
                        $('#createDetailModal').modal('hide');
                        $('#createDetailForm')[0].reset();
                        $('.select2').val(null).trigger('change'); // Reset Select2
                        loadData(); // Reload data after adding
                    }).fail(function(xhr) {
                        Swal.fire('Error', xhr.responseJSON.message, 'error');
                    });
                });

                // Handle delete detail
                $(document).on('click', '.deleteDetailBtn', function() {
                    const detailId = $(this).data('id');

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/product-entry-details/${detailId}`,
                                type: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(data) {
                                    Swal.fire('Berhasil', data.message, 'success');
                                    loadData(); // Reload data after deletion
                                },
                                error: function(xhr) {
                                    Swal.fire('Error', xhr.responseJSON.message, 'error');
                                }
                            });
                        }
                    });
                });

                // Input price formatting
                $('#price').on('input', function() {
                    let value = $(this).val().replace(/\D/g, ''); // Remove non-digit characters
                    $(this).val(formatRupiah(value)); // Format to Rupiah
                });

                // Search functionality
                $('#searchInput').on('input', function() {
                    var value = $(this).val().toLowerCase();

                    // Clear the interval if there's input in the search field
                    if (value.length > 0) {
                        clearInterval(dataLoadInterval);
                    } else {
                        // Restart the interval if the search field is empty
                        startDataLoadInterval();
                    }

                    $('table tbody tr').filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    });
                });
            });
        </script>
    @endsection
