@extends('layout.app')

@section('title', 'Product Exits')

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Data Product Exits</h4>

                            <div class="align-right text-right">
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#importExcelModal">
                                    <i class="fas fa-file-excel"></i> Import Excel
                                </button>
                                <button type="button" class="btn btn-info"
                                    onclick="window.location.href='{{ route('product_exits.export') }}'">
                                    <i class="fas fa-download"></i> Export Excel
                                </button>
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#createProductExitModal">
                                    <i class="fas fa-plus"></i> Tambah Product Exit
                                </button>
                            </div>
                            <br>
                            <div class="search-element">
                                <input id="searchInput" class="form-control" type="search" placeholder="Search"
                                    aria-label="Search">
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table id="example" class="table table-bordered zero-configuration">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Nama Kapal</th>
                                            <th class="text-center">No Exit</th>
                                            <th class="text-center">Tgl Exit</th>
                                            <th class="text-center">Jenis Barang</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="productExitTableBody">
                                        @foreach ($productExits as $index => $exit)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td class="text-center">{{ $exit->nama_kapal }}</td>
                                                <td class="text-center">{{ $exit->no_exit }}</td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($exit->tgl_exit)->format('d F Y') }}</td>
                                                <td class="text-center">{{ $exit->jenis_barang }}</td>
                                                <td class="text-center">{{ number_format($exit->total, 2, ',', '.') }}</td>
                                                <td class="align-middle text-center">
                                                    <span>
                                                        <button data-toggle="modal"
                                                            data-target="#editProductExitModal{{ $exit->id }}"
                                                            type="button" class="btn btn-info">Edit</button>
                                                        <form id="deleteForm-{{ $exit->id }}" method="post"
                                                            action="{{ route('product_exits.destroy', $exit->id) }}"
                                                            style="display:inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-danger"
                                                                onclick="confirmDelete('{{ $exit->id }}')">Delete</button>
                                                        </form>
                                                    </span>
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

        <!-- Tambah Product Exit Modal -->
        <div class="modal fade" id="createProductExitModal" tabindex="-1" role="dialog"
            aria-labelledby="createProductExitModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createProductExitModalLabel">Tambah Product Exit</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('product_exits.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nama_kapal">Nama Kapal:</label>
                                <input type="text" class="form-control" id="nama_kapal" name="nama_kapal"
                                    placeholder="Nama Kapal" required>
                            </div>
                            <div class="form-group">
                                <label for="no_exit">No Exit:</label>
                                <input type="text" class="form-control" id="no_exit" name="no_exit"
                                    placeholder="No Exit" required>
                            </div>
                            <div class="form-group">
                                <label for="tgl_exit">Tgl Exit:</label>
                                <input type="date" class="form-control" id="tgl_exit" name="tgl_exit" required>
                            </div>
                            <div class="form-group">
                                <label for="jenis_barang">Jenis Barang:</label>
                                <input type="text" class="form-control" id="jenis_barang" name="jenis_barang"
                                    placeholder="Jenis Barang" required>
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

        <!-- Modal Edit Product Exit -->
        @foreach ($productExits as $exit)
            <div class="modal fade" id="editProductExitModal{{ $exit->id }}" tabindex="-1" role="dialog"
                aria-labelledby="editProductExitModalLabel{{ $exit->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProductExitModalLabel{{ $exit->id }}">Edit Product Exit
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="editProductExitForm{{ $exit->id }}"
                            action="{{ route('product_exits.update', $exit->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="editNamaKapal{{ $exit->id }}">Nama Kapal:</label>
                                    <input type="text" class="form-control" id="editNamaKapal{{ $exit->id }}"
                                        name="nama_kapal" value="{{ $exit->nama_kapal }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="editNoExit{{ $exit->id }}">No Exit:</label>
                                    <input type="text" class="form-control" id="editNoExit{{ $exit->id }}"
                                        name="no_exit" value="{{ $exit->no_exit }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="editTglExit{{ $exit->id }}">Tgl Exit:</label>
                                    <input type="date" class="form-control" id="editTglExit{{ $exit->id }}"
                                        name="tgl_exit" value="{{ $exit->tgl_exit }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="editJenisBarang{{ $exit->id }}">Jenis Barang:</label>
                                    <input type="text" class="form-control" id="editJenisBarang{{ $exit->id }}"
                                        name="jenis_barang" value="{{ $exit->jenis_barang }}" required>
                                </div>
                                <input type="hidden" name="version" value="{{ $exit->version }}">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Tambah modal untuk Import -->
    <div class="modal fade" id="importExcelModal" tabindex="-1" role="dialog" aria-labelledby="importExcelModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importExcelModalLabel">Import Data Product Exits dari Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('product_exits.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file">Pilih file Excel:</label>
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
        let isRefreshing = true; // Flag untuk mengendalikan refresh

        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    isRefreshing = false; // Hentikan refresh
                    document.getElementById('deleteForm-' + id).submit();
                }
            });
        }

        // Fungsi pencarian dengan debounce
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    let columns = row.querySelectorAll('td');
                    let match = Array.from(columns).some(column => column.textContent.toLowerCase()
                        .includes(filter));
                    row.style.display = match ? '' : 'none';
                });
            }, 300); // delay 300ms untuk debounce
        });

        // Fungsi untuk me-refresh data melalui AJAX jika tidak ada input pencarian
        function refreshProductExits() {
            let searchInput = document.getElementById('searchInput').value.trim();
            if (searchInput === '' && isRefreshing) { // Hanya refresh jika input pencarian kosong dan refresh diizinkan
                $.ajax({
                    url: '{{ route('product_exits.index') }}',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        let tableBody = $('#productExitTableBody');
                        tableBody.empty();
                        data.forEach((exit, index) => {
                            tableBody.append(`
                                <tr>
                                    <td class="text-center">${index + 1}</td>
                                    <td class="text-center">${exit.nama_kapal}</td>
                                    <td class="text-center">${exit.no_exit}</td>
                                    <td class="text-center">${new Date(exit.tgl_exit).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</td>
                                    <td class="text-center">${exit.jenis_barang}</td>
                                    <td class="text-center">${parseFloat(exit.total).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                                    <td class="align-middle text-center">
                                        <span>
                                            <button data-toggle="modal" data-target="#editProductExitModal${exit.id}" type="button" class="btn btn-info">Edit</button>
                                            <form id="deleteForm-${exit.id}" method="post" action="{{ url('product_exits') }}/${exit.id}" style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger" onclick="confirmDelete('${exit.id}')">Delete</button>
                                            </form>
                                        </span>
                                    </td>
                                </tr>
                            `);
                        });
                        isRefreshing = true; // Izinkan refresh setelah berhasil
                    },
                    error: function(err) {
                        console.error('Error fetching product exits:', err);
                        isRefreshing = true; // Izinkan refresh meskipun terjadi kesalahan
                    }
                });
            }
        }

        // Refresh the product exits every 3 seconds
        let refreshInterval = setInterval(refreshProductExits, 3000);

        @if (session('notification'))
            $(document).ready(function() {
                Swal.fire({
                    icon: '{{ session('notification.type') }}',
                    title: '{{ session('notification.title') ?? '' }}',
                    text: '{{ session('notification.message') }}',
                    showConfirmButton: true,
                    confirmButtonText: 'OK',
                    timer: 3000,
                    customClass: {
                        popup: 'swal2-popup-custom',
                        icon: 'swal2-icon-custom',
                        title: 'swal2-title-custom',
                        confirmButton: 'swal2-confirm-button-custom'
                    },
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
            });
        @endif
    </script>
@endsection
