<!DOCTYPE html>
<html>

<head>
    <title>Create User</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        .strength-meter {
            height: 5px;
            background-color: #eee;
            margin-top: -5px;
            margin-bottom: 10px;
            border-radius: 5px;
            overflow: hidden;
        }

        #strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.3s;
        }
    </style>
</head>

<body>

    <div class="d-flex justify-content-center align-items-center" style="min-height:100vh;">

        <div class="form-card">

            <h4 class="text-center mb-3">👤 Create User</h4>

            <form id="regForm">
                @csrf

                <input type="text" name="name" placeholder="Full Name" class="form-control mb-2">

                <input type="text" name="email" id="email" placeholder="Email Address" class="form-control mb-2">

                <div class="input-group mb-2">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">👁</button>
                </div>

                <div class="strength-meter">
                    <div id="strength-bar"></div>
                </div>
                <small id="strength-text" class="mb-2 d-block"></small>

                <input type="password" name="confirm_password" class="form-control mb-2" placeholder="Confirm Password">

                <button type="submit" class="btn btn-primary w-100 mt-3">Create User</button>
                <a href="{{ url('users/list') }}" class="btn btn-outline-secondary w-100 mt-2">View Users List</a>
            </form>

        </div>

    </div>

    <script>

        function togglePassword() {
            let pass = document.getElementById('password');
            pass.type = (pass.type === 'password') ? 'text' : 'password';
        }

        $('#password').on('input', function() {
            let val = $(this).val();
            let strength = 0;
            if (val.length > 5) strength += 25;
            if (val.match(/[A-Z]/)) strength += 25;
            if (val.match(/[0-9]/)) strength += 25;
            if (val.match(/[^A-Za-z0-9]/)) strength += 25;

            let bar = $('#strength-bar');
            let text = $('#strength-text');

            bar.css('width', strength + '%');

            if (strength <= 25) {
                bar.css('background-color', '#dc3545');
                text.text('Weak').css('color', '#dc3545');
            } else if (strength <= 75) {
                bar.css('background-color', '#ffc107');
                text.text('Medium').css('color', '#ffc107');
            } else {
                bar.css('background-color', '#198754');
                text.text('Strong').css('color', '#198754');
            }
        });

        $("#regForm").validate({
            rules: {
                name: { required: true },
                email: {
                    required: true,
                    email: true,
                    remote: {
                        url: "{{ route('users.checkEmail') }}",
                        type: "GET"
                    }
                },
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
                    email: "Enter valid email",
                    remote: "Email already taken"
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

        $("#regForm").submit(function (e) {
            e.preventDefault();

            if (!$(this).valid()) return;

            $.ajax({
                url: "{{ route('users.store') }}",
                method: "POST",
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                success: function (res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'User Created Successfully',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    $("#regForm").reset();
                    $('#strength-bar').css('width', '0%');
                    $('#strength-text').text('');
                },

                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let firstError = Object.values(errors);
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: firstError
                        });
                    }
                    else if (xhr.status === 419) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Expired',
                            text: 'Session expired. Refreshing...'
                        }).then(() => {
                            location.reload();
                        });
                    }
                    else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong!'
                        });
                    }
                }
            });
        });

    </script>

</body>

</html>