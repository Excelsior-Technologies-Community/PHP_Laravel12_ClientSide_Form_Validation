<!DOCTYPE html>
<html>

<head>
    <title>Users List</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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
            </div>

            <!-- SUCCESS MESSAGE -->
            @if(session('success'))
                <div class="alert alert-success text-center" id="successAlert">
                    {{ session('success') }}
                </div>
            @endif

            <!-- SEARCH -->
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
                            <th width="130">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>#{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <form action="{{ route('users.delete', $user->id) }}" method="POST"
                                     onsubmit="return confirmDelete()">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm w-100">Delete</button>
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
        setTimeout(() => {
            let alert = document.getElementById('successAlert');
            if (alert) alert.style.display = 'none';
        }, 3000);

        function confirmDelete() {
            return confirm("Are you sure you want to delete?");
        }
    </script>

</body>

</html>