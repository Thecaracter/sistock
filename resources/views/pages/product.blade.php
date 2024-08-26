@extends('layout.app')

@section('title', 'Product')

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Data Produk</h4>
                            <div class="row text-right">
                                <div class="col">
                                    <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#uploadExcelModal">
                                        <i class="fas fa-file-excel"></i> Import Data
                                    </button>
                                    <button type="button" class="btn btn-info"
                                        onclick="window.location.href='{{ route('products.export.excel') }}'">
                                        <i class="fas fa-download"></i> Export Data
                                    </button>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#createProductModal">
                                        <i class="fas fa-plus"></i> Tambah Produk
                                    </button>

                                </div>
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
                                            <th class="text-center">Nama</th>
                                            <th class="text-center">Deskripsi</th>
                                            <th class="text-center">Gambar</th>
                                            <th class="text-center">Detail Stock</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $index => $product)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td class="text-center">{{ $product->name }}</td>
                                                <td class="text-center">{{ $product->description }}</td>
                                                <td class="text-center">
                                                    <img src="{{ file_exists(public_path('fotoproduct/' . basename($product->image))) ? asset('fotoproduct/' . basename($product->image)) : asset('foto/notfound.png') }}"
                                                        alt="{{ $product->name }}" style="width: 100px;">
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-info" data-toggle="modal"
                                                        data-target="#stockDetailModal{{ $product->id }}">
                                                        <i class="fas fa-info-circle"></i> Detail Stock
                                                    </button>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span>
                                                        <button data-toggle="modal"
                                                            data-target="#editProductModal{{ $product->id }}"
                                                            type="button" class="btn btn-info">Edit</button>
                                                        <form id="deleteForm-{{ $product->id }}" method="post"
                                                            action="{{ route('product.destroy', $product->id) }}"
                                                            style="display:inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-danger"
                                                                onclick="confirmDelete('{{ $product->id }}')">Delete</button>
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

        <!-- Tambah Produk Modal -->
        <div class="modal fade" id="createProductModal" tabindex="-1" role="dialog"
            aria-labelledby="createProductModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createProductModalLabel">Tambah Produk</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Nama:</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Nama"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="description">Deskripsi:</label>
                                <textarea class="form-control" id="description" name="description" placeholder="Deskripsi" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="image">Gambar:</label>
                                <input type="file" class="form-control" id="image" name="image"
                                    accept="image/*" required>
                                <img id="previewImage" src="#" alt="Preview"
                                    style="display:none; width: 100px; margin-top: 10px;">
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


        <!-- Modal Edit Produk -->
        @foreach ($products as $product)
            <div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1" role="dialog"
                aria-labelledby="editProductModalLabel{{ $product->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProductModalLabel{{ $product->id }}">Edit Produk</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="editProductForm{{ $product->id }}"
                            action="{{ route('product.update', $product->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="editName{{ $product->id }}">Nama:</label>
                                    <input type="text" class="form-control" id="editName{{ $product->id }}"
                                        name="name" value="{{ $product->name }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="editDescription{{ $product->id }}">Deskripsi:</label>
                                    <textarea class="form-control" id="editDescription{{ $product->id }}" name="description" required>{{ $product->description }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="editImage{{ $product->id }}">Gambar:</label>
                                    <input type="file" class="form-control" id="editImage{{ $product->id }}"
                                        name="image" accept="image/*">
                                    <img id="previewImage{{ $product->id }}"
                                        src="{{ file_exists(public_path('fotoproduct/' . basename($product->image))) ? asset('fotoproduct/' . basename($product->image)) : asset('foto/notfound.jpg') }}"
                                        alt="{{ $product->name }}" style="width: 100px; margin-top: 10px;">
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
            <!-- Modal Detail Stock -->
            <div class="modal fade" id="stockDetailModal{{ $product->id }}" tabindex="-1" role="dialog"
                aria-labelledby="stockDetailModalLabel{{ $product->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="stockDetailModalLabel{{ $product->id }}">Detail Stock -
                                {{ $product->name }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tanggal Masuk</th>
                                            <th>Jumlah</th>
                                            <th>Harga</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($product->productEntries as $entry)
                                            <tr>
                                                <td>{{ $entry->entry_date }}</td>
                                                <td>{{ $entry->quantity }}</td>
                                                <td>{{ $entry->price }}</td>
                                                <td>{{ $entry->total }}</td>
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
        @endforeach
        <!-- Modal Impor Excel-->
        <div class="modal fade" id="uploadExcelModal" tabindex="-1" role="dialog"
            aria-labelledby="uploadExcelModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadExcelModalLabel">Upload File Excel</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('products.import.excel') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="file">Pilih File Excel</label>
                                <input type="file" name="file" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Import Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                // Preview image for "Tambah Produk" form
                $('#image').change(function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $('#previewImage').attr('src', e.target.result).show();
                        }
                        reader.readAsDataURL(file);
                    } else {
                        $('#previewImage').hide();
                    }
                });
                $('#createProductModal form').on('submit', function() {
                    $(this).find('button[type="submit"]').prop('disabled', true);
                });

                // Prevent multiple clicks on edit buttons
                @foreach ($products as $product)
                    $('#editProductForm{{ $product->id }}').on('submit', function() {
                        $(this).find('button[type="submit"]').prop('disabled', true);
                    });
                @endforeach


                // Preview image for "Edit Product" form
                @foreach ($products as $product)
                    $('#editImage{{ $product->id }}').change(function(event) {
                        const file = event.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                $('#previewImage{{ $product->id }}').attr('src', e.target.result);
                            }
                            reader.readAsDataURL(file);
                        } else {
                            $('#previewImage{{ $product->id }}').attr('src',
                                '{{ file_exists(public_path('fotoproduct/' . basename($product->image))) ? asset('fotoproduct/' . basename($product->image)) : asset('fotoproduct/notfound.jpg') }}'
                            );
                        }
                    });
                @endforeach

                // Search functionality
                $('#searchInput').on('keyup', function() {
                    var value = $(this).val().toLowerCase();
                    $('table tbody tr').filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    });
                });

                // Confirmation for delete
                window.confirmDelete = function(productId) {
                    Swal.fire({
                        title: 'Yakin Mo Ngapus Bro?',
                        text: "Nggak bakal bisa balik lo",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, saya yakin!',
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return new Promise((resolve) => {
                                $('#deleteForm-' + productId).submit();
                                resolve();
                            });
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    });
                };
            });
        </script>

        @if (session('notification'))
            <script>
                $(document).ready(function() {
                    const {
                        title,
                        text,
                        type
                    } = @json(session('notification'));
                    Swal.fire(title, text, type);
                });
            </script>
        @endif
    </div>
@endsection
