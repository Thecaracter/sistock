@extends('layout.app')

@section('title', 'Product Exit Details')

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Detail Exit Produk untuk {{ $productExit->no_exit }}</h4>

                            <div class="mb-3">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#selectProductEntryModal">
                                    <i class="fas fa-plus"></i> Tambah Detail Exit Produk
                                </button>
                                <a href="{{ route('product-exit-details.export', $productExit->id) }}"
                                    class="btn btn-success">
                                    <i class="fas fa-file-excel"></i> Export Excel
                                </a>
                                <button type="button" class="btn btn-info" data-toggle="modal"
                                    data-target="#importExcelModal">
                                    <i class="fas fa-file-import"></i> Import Excel
                                </button>
                            </div>
                            <br>
                            <div class="search-element">
                                <input id="searchInput" class="form-control" type="search" placeholder="Search"
                                    aria-label="Search">
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Nama Produk</th>
                                            <th class="text-center">Jumlah</th>
                                            <th class="text-center">Harga</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($productExitDetails as $index => $detail)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $detail->productEntryDetail->product->name }}</td>
                                                <td class="text-center">{{ $detail->quantity }}</td>
                                                <td class="text-right">{{ number_format($detail->price, 2) }}</td>
                                                <td class="text-right">{{ number_format($detail->total, 2) }}</td>
                                                <td class="text-center">
                                                    <button class="btn btn-danger delete-product-exit-detail"
                                                        data-id="{{ $detail->id }}">
                                                        Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Import Excel Modal -->
        <div class="modal fade" id="importExcelModal" tabindex="-1" role="dialog" aria-labelledby="importExcelModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importExcelModalLabel">Import Excel</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('product-exit-details.import', $productExit->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="excel_file">Choose Excel File</label>
                                <input type="file" class="form-control-file" id="excel_file" name="excel_file" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal untuk memilih product entries -->
        <div class="modal fade" id="selectProductEntryModal" tabindex="-1" role="dialog"
            aria-labelledby="selectProductEntryModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="selectProductEntryModalLabel">Pilih Produk untuk Detail Exit</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" id="searchModal" placeholder="Cari produk...">
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nama Produk</th>
                                        <th class="text-center">Harga</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="productEntryList">
                                    @foreach ($productEntryDetails as $index => $entry)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $entry->product->name }}</td>
                                            <td class="text-right">Rp {{ number_format($entry->price, 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                <button class="btn btn-success select-product-btn"
                                                    data-id="{{ $entry->id }}"
                                                    data-name="{{ $entry->product->name }}"
                                                    data-price="{{ $entry->price }}">
                                                    Pilih
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal untuk menambah detail exit produk -->
        <div class="modal fade" id="createProductExitDetailModal" tabindex="-1" role="dialog"
            aria-labelledby="createProductExitDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createProductExitDetailModalLabel">Tambah Detail Exit Produk</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="productExitDetailForm" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="selected_product">Produk yang Dipilih</label>
                                <input type="text" id="selected_product" class="form-control" readonly>
                                <input type="hidden" name="product_entry_detail_id" id="product_entry_detail_id"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="quantity">Jumlah</label>
                                <input type="number" name="quantity" id="quantity" class="form-control"
                                    min="1" required>
                            </div>
                            <div class="form-group">
                                <label for="price">Harga</label>
                                <input type="number" name="price" id="price" class="form-control" min="0"
                                    step="0.01" required>
                            </div>
                            <div class="form-group">
                                <label for="total">Total</label>
                                <input type="text" id="total" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="button" class="btn btn-primary" id="saveProductExitDetail">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Event listener untuk tombol pilih produk
            document.querySelectorAll('.select-product-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const selectedProductId = this.getAttribute('data-id');
                    const selectedProductName = this.getAttribute('data-name');
                    const selectedProductPrice = this.getAttribute('data-price');

                    // Isi form di modal kedua dengan data yang dipilih
                    document.getElementById('selected_product').value = selectedProductName;
                    document.getElementById('product_entry_detail_id').value = selectedProductId;
                    document.getElementById('price').value = selectedProductPrice;

                    // Sembunyikan modal pertama dan tampilkan modal kedua
                    $('#selectProductEntryModal').modal('hide');
                    $('#createProductExitDetailModal').modal('show');
                });
            });

            // Hitung total setiap kali jumlah atau harga berubah
            document.getElementById('quantity').addEventListener('input', calculateTotal);
            document.getElementById('price').addEventListener('input', calculateTotal);

            function calculateTotal() {
                const quantity = parseFloat(document.getElementById('quantity').value) || 0;
                const price = parseFloat(document.getElementById('price').value) || 0;
                const total = quantity * price;
                document.getElementById('total').value = total.toFixed(2);
            }

            // Fungsi AJAX untuk menyimpan detail exit produk
            document.getElementById('saveProductExitDetail').addEventListener('click', function() {
                var formData = $('#productExitDetailForm').serialize();
                var url = "{{ route('productExitDetails.store', ['productExitId' => $productExit->id]) }}";

                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            title: 'Sukses!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#createProductExitDetailModal').modal('hide');
                                location.reload();
                            }
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON.message || 'Terjadi kesalahan.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });


        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchModal = document.getElementById('searchModal');
            const productEntryList = document.getElementById('productEntryList');
            const productRows = productEntryList.getElementsByTagName('tr');

            searchModal.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();

                for (let i = 0; i < productRows.length; i++) {
                    const productName = productRows[i].getElementsByTagName('td')[1].textContent
                        .toLowerCase();
                    const productPrice = productRows[i].getElementsByTagName('td')[2].textContent.replace(
                        /[^\d]/g, '');

                    if (productName.includes(searchTerm) || productPrice.includes(searchTerm)) {
                        productRows[i].style.display = '';
                    } else {
                        productRows[i].style.display = 'none';
                    }
                }
            });
        });
        $('#searchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('table tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Event listener untuk tombol hapus
            document.querySelectorAll('.delete-product-exit-detail').forEach(button => {
                button.addEventListener('click', function() {
                    const detailId = this.getAttribute('data-id');

                    // Konfirmasi dengan SweetAlert
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Anda akan menghapus detail exit produk ini!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Mengirim permintaan AJAX untuk menghapus detail
                            $.ajax({
                                type: 'DELETE',
                                url: `/product-exit-detail/${detailId}`,
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    Swal.fire(
                                        'Dihapus!',
                                        response.message,
                                        'success'
                                    ).then(() => {
                                        location
                                            .reload(); // Refresh halaman
                                    });
                                },
                                error: function(xhr) {
                                    Swal.fire(
                                        'Error!',
                                        xhr.responseJSON.message ||
                                        'Terjadi kesalahan saat menghapus.',
                                        'error'
                                    );
                                }
                            });
                        }
                    });
                });
            });
        });
    </script>

@endsection
