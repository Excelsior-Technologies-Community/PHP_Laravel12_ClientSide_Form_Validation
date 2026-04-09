<!DOCTYPE html>
<html>

<head>
    <title>Create User</title>

    <!-- CSRF TOKEN (VERY IMPORTANT) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- VALIDATION -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <style>
        body {
            background: linear-gradient(135deg, #e3f2fd, #f8f9fa);
            min-height: 100vh;
        }

        .form-card {
            border-radius: 15px;
            padding: 30px;
            background: #fff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .form-control {
            border-radius: 8px;
        }

        .btn {
            border-radius: 8px;
        }

        input.error {
            border: 1px solid red !important;
        }

        small.text-danger {
            font-size: 13px;
        }

        .input-group .form-control {
            border-right: 0;
        }

        .input-group .btn {
            border-left: 0;
        }
    </style>
</head>

<body>

    <div class="d-flex justify-content-center align-items-center" style="min-height:100vh;">

        <div class="form-card">

            <h4 class="text-center mb-3">👤 Create User</h4>

            <div id="successMsg"></div>

            <form id="regForm">
                @csrf

                <!-- NAME -->
                <input type="text" name="name" placeholder="Full Name" class="form-control mb-2">

                <!-- EMAIL -->
                <input type="text" name="email" placeholder="Email Address" class="form-control mb-2">

                <!-- PASSWORD -->
                <div class="input-group mb-2">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">👁</button>
                </div>

                <!-- CONFIRM PASSWORD -->
                <input type="password" name="confirm_password" class="form-control mb-2" placeholder="Confirm Password">

                <button class="btn btn-primary w-100 mt-2">Create Account</button>
            </form>

        </div>

    </div>

    <script>

        // PASSWORD TOGGLE
        function togglePassword() {
            let pass = document.getElementById('password');
            pass.type = (pass.type === 'password') ? 'text' : 'password';
        }

        // VALIDATION
        $("#regForm").validate({
            rules: {
                name: { required: true },
                email: { required: true, email: true },
                password: { required: true, minlength: 5 },
                confirm_password: {
                    required: true,
                    equalTo: "#password"
                }
            },

            messages: {
                name: { required: "Name is required" },
                email: {
                    required: "Email is required",
                    email: "Enter valid email"
                },
                password: {
                    required: "Password is required",
                    minlength: "Minimum 5 characters required"
                },
                confirm_password: {
                    required: "Confirm password required",
                    equalTo: "Passwords do not match"
                }
            },

            errorElement: "small",
            errorClass: "text-danger d-block",

            errorPlacement: function (error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            }
        });

        // AJAX SUBMIT (FIXED 419 + ERROR)
        $("#regForm").submit(function (e) {
            e.preventDefault();

            if (!$(this).valid()) return;

            $.ajax({
                url: "{{ route('users.store') }}",
                method: "POST",
                data: $(this).serialize(),

                // ✅ CSRF FIX
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                success: function (res) {
                    $("#successMsg").html('<div class="alert alert-success text-center">✅ User Created Successfully</div>');
                    $("#regForm")[0].reset();
                },

                error: function (xhr) {

                    // ✅ VALIDATION ERROR
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let firstError = Object.values(errors)[0][0];
                        alert(firstError);
                    }

                    // ✅ CSRF ERROR
                    else if (xhr.status === 419) {
                        alert("Session expired. Please refresh page.");
                    }
                    else {
                        alert("Something went wrong!");
                    }
                }
            });
        });

    </script>

</body>

</html>