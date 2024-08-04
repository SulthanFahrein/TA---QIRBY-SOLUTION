@extends('layouts.master')
@section('judul')
Data User
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('template/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>

    <script>
        // edit user
        $(function () {
            $("#example1").DataTable();

            $('.formUpdate').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function (res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Saved!',
                            text: 'Your changes have been saved.'
                        }).then(() => {
                            $('#editUserModal').modal('hide');
                            location.reload();
                        });
                    },
                    error: function (err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat mengupdate data user'
                        });
                    }
                });
            });
        });

        function userEdit(id) {
            const urlTemplate = "{{ route('users.edit', ':id') }}";
            const url = urlTemplate.replace(':id', id);
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    id: id
                },
                success: function (res) {
                    $('#editUserModal').modal('show');
                    $('.formUpdate').attr('action', "{{ route('users.update', ':id') }}".replace(':id', id));
                    $('.modal-body').html(`
                                                                <input type="hidden" name="id" value="${res.id}">
                                                                <div class="form-group">
                                                                    <label for="name_user">Name User</label>
                                                                    <input type="text" class="form-control" name="name_user" required value="${res.name_user}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="phone_user">Phone User</label>
                                                                    <input type="text" class="form-control" name="phone_user" required value="${res.phone_user}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="email_user">Email User</label>
                                                                    <input type="email" class="form-control" name="email_user" required value="${res.email_user}">
                                                                </div>
                                                            `);
                }
            });
        }

        function modalClose() {
            $('#editUserModal').modal('hide');
        }

        // delete user
        // delete user
        function deleteUser(userId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('data_user.destroy', ':id') }}'.replace(':id', userId),
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            if (response.error) {
                                Swal.fire({
                                    title: 'Gagal',
                                    text: response.error,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                Swal.fire({
                                    title: 'Success',
                                    text: response.success,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error(status);
                            Swal.fire({
                                title: 'Error',
                                text: 'Cannot delete this user because this user has a schedule.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endpush

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/datauser.css') }}" />
<br>
<!-- Alert -->
@if (session()->has('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="card-body">
    <div class="table-responsive">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID User</th>
                    <th>Name User</th>
                    <th>Phone User</th>
                    <th>Email User</th>
                    <th>Action User</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $us)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $us->name_user }}</td>
                        <td>{{ $us->phone_user }}</td>
                        <td>{{ $us->email_user }}</td>
                        <td>
                            <button type="button" class="btn btn-edit" onclick="userEdit({{ $us->id }})">Edit</button>
                            <button type="button" class="btn btn-danger" onclick="deleteUser({{ $us->id }})">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Edit User Modal -->
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Update Data User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            onclick="modalClose()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" class="formUpdate">
                        @csrf
                        @method('put')
                        <div class="modal-body"></div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" style="background-color:#00a5a7">Update
                                User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection