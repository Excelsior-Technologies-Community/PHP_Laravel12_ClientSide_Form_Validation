<!DOCTYPE html>
<html>

<head>
    <title>Users List</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: linear-gradient(135deg, #eef2f7, #dbe6f6);
            min-height: 100vh;
        }

        .main-card {
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            background: #fff;
        }

        .table th {
            background: #0d6efd;
            color: white;
        }

        .search-box input {
            border-radius: 8px;
        }

        .btn {
            border-radius: 8px;
        }
    </style>
</head>

<body>

    <div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">
        <div class="main-card p-4 w-100" style="max-width: 900px;">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>👥 Users Management</h4>
                <a href="{{ url('users/create') }}" class="btn btn-success btn-sm">Add New User</a>
            </div>

            <form method="GET" class="mb-3 d-flex search-box">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="🔍 Search by name or email..." class="form-control me-2">
                <button class="btn btn-primary">Search</button>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>#{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button type="button" onclick="editUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')" class="btn btn-warning btn-sm w-100">Edit</button>
                                        <button type="button" onclick="confirmDelete({{ $user->id }})" class="btn btn-danger btn-sm w-100">Delete</button>
                                    </div>
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('users.delete', $user->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">
                                    No users found 😔
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 2500,
                showConfirmButton: false
            });
        @endif

        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        function editUser(id, name, email) {
            Swal.fire({
                title: 'Edit User',
                html: `
                    <div class="text-start mt-2">
                        <label class="form-label">Name</label>
                        <input id="swal-name" class="form-control mb-3" value="${name}">
                        <label class="form-label">Email</label>
                        <input id="swal-email" class="form-control" value="${email}">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Update',
                preConfirm: () => {
                    const newName = document.getElementById('swal-name').value;
                    const newEmail = document.getElementById('swal-email').value;
                    
                    if (!newName || !newEmail) {
                        Swal.showValidationMessage('Both fields are required');
                        return false;
                    }
                    return { name: newName, email: newEmail };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/users/update/${id}`,
                        method: "POST",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            name: result.value.name,
                            email: result.value.email
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: response.success,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            let errorMsg = 'Something went wrong';
                            if (xhr.status === 422) {
                                errorMsg = Object.values(xhr.responseJSON.errors);
                            } else if (xhr.status === 419) {
                                errorMsg = 'Session expired. Please refresh.';
                            }
                            Swal.fire('Error!', errorMsg, 'error');
                        }
                    });
                }
            });
        }
    </script>

</body>

</html> 