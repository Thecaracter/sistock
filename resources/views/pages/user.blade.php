@extends('layout.app')

@section('title', 'User')

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Data User</h4>

                            <div class="align-right text-right">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#createUserModal">
                                    Tambah Pengguna
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
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $index => $user)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td class="text-center">{{ $user->name }}</td>
                                                <td class="text-center">{{ $user->email }}</td>
                                                <td class="align-middle text-center">
                                                    <span>
                                                        <button data-toggle="modal"
                                                            data-target="#editUserModal{{ $user->id }}" type="button"
                                                            class="btn btn-info">Edit</button>
                                                        <form id="deleteForm-{{ $user->id }}" method="post"
                                                            action="{{ route('user.destroy', $user->id) }}"
                                                            style="display:inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-danger"
                                                                onclick="confirmDelete('{{ $user->id }}')">Delete</button>
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

        <!-- Tambah Pengguna Modal -->
        <div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createUserModalLabel">Tambah Pengguna</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('user.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Nama:</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Nama"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Password" required>
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

        <!-- Modal Edit Pengguna -->
        @foreach ($users as $user)
            <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" role="dialog"
                aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">Edit Pengguna</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="editUserForm{{ $user->id }}" action="{{ route('user.update', $user->id) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="editName{{ $user->id }}">Nama:</label>
                                    <input type="text" class="form-control" id="editName{{ $user->id }}"
                                        name="name" value="{{ $user->name }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="editEmail{{ $user->id }}">Email:</label>
                                    <input type="email" class="form-control" id="editEmail{{ $user->id }}"
                                        name="email" value="{{ $user->email }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="editPassword{{ $user->id }}">Password:</label>
                                    <input type="password" class="form-control" id="editPassword{{ $user->id }}"
                                        name="password" placeholder="Isi jika ingin mengubah">
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

                window.confirmDelete = function(userId) {
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
                            document.getElementById('deleteForm-' + userId).submit();
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
