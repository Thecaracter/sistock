@extends('layout.app')

@section('title', 'Product Entries')

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Data Product Entries</h4>

                            <div class="align-right text-right">
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#importExcelModal">
                                    <i class="fas fa-file-excel"></i> Import Excel
                                </button>
                                <button type="button" class="btn btn-info"
                                    onclick="window.location.href='{{ route('product-entries.export') }}'">
                                    <i class="fas fa-download"></i> Export Excel
                                </button>
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#createProductEntryModal">
                                    <i class="fas fa-plus"></i> Tambah Product Entry
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
                                            <th class="text-center">No Permintaan</th>
                                            <th class="text-center">Tgl Permintaan</th>
                                            <th class="text-center">Jenis Barang</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Detail Masuk</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($productEntries as $index => $entry)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td class="text-center">{{ $entry->nama_kapal }}</td>
                                                <td class="text-center">{{ $entry->no_permintaan }}</td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($entry->tgl_permintaan)->format('d F Y') }}
                                                </td>
                                                <td class="text-center">{{ $entry->jenis_barang }}</td>
                                                <td class="text-center">Rp {{ number_format($entry->total, 2, ',', '.') }}
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('product-entry-details.index', $entry->id) }}"
                                                        class="btn btn-primary">
                                                        <i class="fas fa-info-circle"></i> Detail
                                                    </a>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span>
                                                        <button data-toggle="modal"
                                                            data-target="#editProductEntryModal{{ $entry->id }}"
                                                            type="button" class="btn btn-info">Edit</button>
                                                        <form id="deleteForm-{{ $entry->id }}" method="post"
                                                            action="{{ route('product_entries.destroy', $entry->id) }}"
                                                            style="display:inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-danger"
                                                                onclick="confirmDelete('{{ $entry->id }}')">Delete</button>
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

        <!-- Tambah Product Entry Modal -->
        <div class="modal fade" id="createProductEntryModal" tabindex="-1" role="dialog"
            aria-labelledby="createProductEntryModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createProductEntryModalLabel">Tambah Product Entry</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('product_entries.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nama_kapal">Nama Kapal:</label>
                                <input type="text" class="form-control" id="nama_kapal" name="nama_kapal"
                                    placeholder="Nama Kapal" required>
                            </div>
                            <div class="form-group">
                                <label for="no_permintaan">No Permintaan:</label>
                                <input type="text" class="form-control" id="no_permintaan" name="no_permintaan"
                                    placeholder="No Permintaan" required>
                            </div>
                            <div class="form-group">
                                <label for="tgl_permintaan">Tgl Permintaan:</label>
                                <input type="date" class="form-control" id="tgl_permintaan" name="tgl_permintaan"
                                    required>
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

        <!-- Modal Edit Product Entry -->
        @foreach ($productEntries as $entry)
            <div class="modal fade" id="editProductEntryModal{{ $entry->id }}" tabindex="-1" role="dialog"
                aria-labelledby="editProductEntryModalLabel{{ $entry->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProductEntryModalLabel{{ $entry->id }}">Edit Product Entry
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="editProductEntryForm{{ $entry->id }}"
                            action="{{ route('product_entries.update', $entry->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="editNamaKapal{{ $entry->id }}">Nama Kapal:</label>
                                    <input type="text" class="form-control" id="editNamaKapal{{ $entry->id }}"
                                        name="nama_kapal" value="{{ $entry->nama_kapal }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="editNoPermintaan{{ $entry->id }}">No Permintaan:</label>
                                    <input type="text" class="form-control" id="editNoPermintaan{{ $entry->id }}"
                                        name="no_permintaan" value="{{ $entry->no_permintaan }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="editTglPermintaan{{ $entry->id }}">Tgl Permintaan:</label>
                                    <input type="date" class="form-control" id="editTglPermintaan{{ $entry->id }}"
                                        name="tgl_permintaan" value="{{ $entry->tgl_permintaan }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="editJenisBarang{{ $entry->id }}">Jenis Barang:</label>
                                    <input type="text" class="form-control" id="editJenisBarang{{ $entry->id }}"
                                        name="jenis_barang" value="{{ $entry->jenis_barang }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
        <!-- Modal Import Excel -->
        <div class="modal fade" id="importExcelModal" tabindex="-1" role="dialog"
            aria-labelledby="importExcelModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importExcelModalLabel">Import Excel</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('product-entries.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="excel_file">Pilih file Excel</label>
                                <input type="file" class="form-control-file" id="excel_file" name="excel_file"
                                    accept=".xlsx, .xls, .csv" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $('#searchInput').on('keyup', function() {
                    var value = $(this).val().toLowerCase();
                    $('table tbody tr').filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    });
                });

                window.confirmDelete = function(entryId) {
                    Swal.fire({
                        title: 'Yakin Mo Ngapus Bro?',
                        text: "Nggak bakal bisa balik lo",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, saya yakin!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('deleteForm-' + entryId).submit();
                        }
                    });
                };
            });
        </script>

        @if (session('notification'))
            <script>
                $(document).ready(function() {
                    Swal.fire({
                        icon: '{{ session('notification.type') }}', // Ikon berdasarkan tipe notifikasi (e.g., success, error)
                        title: '{{ session('notification.title') ?? '' }}', // Judul notifikasi, bisa kosong jika tidak ada
                        text: '{{ session('notification.message') }}', // Pesan notifikasi
                        showConfirmButton: true, // Tampilkan tombol konfirmasi
                        confirmButtonText: 'OK', // Teks pada tombol konfirmasi
                        timer: 3000, // Durasi tampilan notifikasi dalam milidetik
                        customClass: {
                            popup: 'swal2-popup-custom', // Kelas CSS custom untuk popup
                            icon: 'swal2-icon-custom', // Kelas CSS custom untuk ikon
                            title: 'swal2-title-custom', // Kelas CSS custom untuk judul
                            confirmButton: 'swal2-confirm-button-custom' // Kelas CSS custom untuk tombol konfirmasi
                        },
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown' // Animasi saat notifikasi muncul
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp' // Animasi saat notifikasi menghilang
                        }
                    });
                });
            </script>
        @endif
    </div>
@endsection
