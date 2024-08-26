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

                            <div class="align-right text-right">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#createProductModal">
                                    Tambah Produk
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
                                            <th class="text-center">Nama</th>
                                            <th class="text-center">Deskripsi</th>
                                            <th class="text-center">Gambar</th>
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
                                                    <img src="{{ file_exists(public_path('fotoproduct/' . basename($product->image))) ? asset('fotoproduct/' . basename($product->image)) : asset('fotoproduct/notfound.jpg') }}"
                                                        alt="{{ $product->name }}" style="width: 100px;">
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
                                <input type="file" class="form-control" id="image" name="image" required>
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
                                        name="image">
                                    <img src="{{ file_exists(public_path('fotoproduct/' . basename($product->image))) ? asset('fotoproduct/' . basename($product->image)) : asset('fotoproduct/notfound.jpg') }}"
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
        @endforeach

        <script>
            $(document).ready(function() {
                $('#searchInput').on('keyup', function() {
                    var value = $(this).val().toLowerCase();
                    $('table tbody tr').filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    });
                });

                window.confirmDelete = function(productId) {
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
                            document.getElementById('deleteForm-' + productId).submit();
                        }
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
    @endsection
