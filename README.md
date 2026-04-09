#  PHP_Laravel12_ClientSide_Form_Validation
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> origin/development

## Introduction

Client-side form validation improves user experience by validating user
input instantly in the browser before sending data to the server.

This project demonstrates **Client-Side Form Validation in Laravel 12
using jQuery Validation Plugin**, along with **server-side validation**
for security.

This project is beginner-friendly and suitable for freshers, academic
submissions, and interviews.

------------------------------------------------------------------------

##  Project Overview

**Project Name:** PHP_Laravel12_ClientSide_Form_Validation\
**Framework:** Laravel 12\
**Validation Types:** - Client-Side Validation (jQuery) - Server-Side
Validation (Laravel)

###  Objective

-   Validate form inputs in browser
-   Prevent invalid submissions
-   Display instant error messages
-   Save data securely
-   Show success message after submission

------------------------------------------------------------------------

##  Tech Stack

-   Laravel 12
-   PHP 8+
-   jQuery
-   jQuery Validate Plugin
-   Bootstrap 5
-   MySQL

------------------------------------------------------------------------

##  Step 1: Create Laravel 12 Project

``` bash
composer create-project laravel/laravel PHP_Laravel12_ClientSide_Form_Validation "12.*"
cd PHP_Laravel12_ClientSide_Form_Validation
```

------------------------------------------------------------------------

##  Step 2: Environment Configuration (.env)

``` env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=client_form_validation
DB_USERNAME=root
DB_PASSWORD=
```

After create database using this command:

``` bash
php artisan migrate
```

------------------------------------------------------------------------

##  Step 3: User Migration (Default Use)

###  Migration File Location:

database/migrations/xxxx_xx_xx_create_users_table.php

``` php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->rememberToken();
    $table->timestamps();
});
```

Run:

``` bash
php artisan migrate
```

------------------------------------------------------------------------

##  Step 4: User Model (Default Use)

###  File Location

app/Models/User.php

``` php
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
```

------------------------------------------------------------------------

##  Step 5: Controller

``` bash
php artisan make:controller FormController
```

File: App\Http\Controllers\FormController

``` php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

/**
 * FormController
 * 
 * This controller handles:
 * - Showing the user creation form
 * - Storing user data after validation
 */
class FormController extends Controller
{
    /**
     * Show the user creation form
     * 
     * This method simply returns the Blade view
     * where the form is displayed.
     */
    public function create()
    {
        return view('createUser');
    }

    /**
     * Store the submitted form data
     * 
     * This method:
     * 1. Validates the request data
     * 2. Encrypts the password
     * 3. Saves user data into the database
     * 4. Redirects back with a success message
     */
    public function store(Request $request)
    {
        // Server-side validation for security
        $validatedData = $request->validate([
            'name'     => 'required',              // Name field is required
            'email'    => 'required|email|unique:users', // Email must be valid and unique
            'password' => 'required|min:5',         // Password must be at least 5 characters
        ]);

        // Encrypt the password before saving
        $validatedData['password'] = bcrypt($validatedData['password']);

        // Store user data into the users table
        User::create($validatedData);

        // Redirect back to form with success message
        return back()->with('success', 'User successfully created!');
    }
}
```

------------------------------------------------------------------------

##  Step 6: Routes

File: routes/web.php

``` php
use App\Http\Controllers\FormController;

Route::get('/users/create', [FormController::class, 'create']);
Route::post('/users/create', [FormController::class, 'store'])->name('users.store');
```

------------------------------------------------------------------------

##  Step 7: Blade View & Client-Side Validation

File: resources/views/createUser.blade.php

-   Uses jQuery Validate Plugin
-   Instant validation feedback
-   Prevents invalid form submission

Validation Rules: - Name: Required - Email: Required & valid - Password:
Minimum 5 characters - Confirm Password: Must match


```html
<!DOCTYPE html>
<html>
<head>
    <!-- Page Title -->
    <title>Laravel 12 Client-Side Form Validation</title>

    <!-- Bootstrap CSS for basic styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- jQuery Validation Plugin for client-side form validation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <!-- Custom style for validation error messages -->
    <style>
        label.error {
            color: #dc3545;      /* Red color for error text */
            font-size: 14px;     /* Slightly smaller font */
        }
    </style>
</head>
<body>

<!-- Main container -->
<div class="container mt-5">

    <!-- Success message displayed after user creation -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- User Registration Form -->
    <form id="regForm" method="POST" action="{{ route('users.store') }}">
        @csrf <!-- CSRF protection token -->

        <!-- Name Field -->
        <div class="mb-3">
            <label for="inputName" class="form-label">Name:</label>
            <input type="text" name="name" id="inputName" class="form-control">
        </div>

        <!-- Email Field -->
        <div class="mb-3">
            <label for="inputEmail" class="form-label">Email:</label>
            <input type="text" name="email" id="inputEmail" class="form-control">
        </div>

        <!-- Password Field -->
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>

        <!-- Confirm Password Field -->
        <div class="mb-3">
            <label for="inputPassword" class="form-label">Confirm Password:</label>
            <input type="password" name="confirm_password" id="inputPassword" class="form-control">
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<!-- jQuery Client-Side Validation Script -->
<script>
$(document).ready(function(){

    // Apply validation on form submit
    $("#regForm").validate({

        // Validation rules
        rules: {
            name: { 
                required: true            // Name must be filled
            },
            email: { 
                required: true,           // Email is required
                email: true               // Must be valid email format
            },
            password: { 
                required: true,           // Password is required
                minlength: 5              // Minimum 5 characters
            },
            confirm_password: {
                required: true,           // Confirm password required
                equalTo: "#password"      // Must match password field
            }
        },

        // Custom error messages
        messages: {
            name: "Name is required",
            email: {
                required: "Email is required",
                email: "Enter a valid email"
            },
            password: {
                required: "Password is required",
                minlength: "Minimum 5 characters"
            },
            confirm_password: {
                required: "Confirm password is required",
                equalTo: "Passwords do not match"
            }
        }
    });
});
</script>

</body>
</html>
```
------------------------------------------------------------------------


##  Step 8: Run Project

``` bash
php artisan serve
```

Visit:

```
http://127.0.0.1:8000/users/create
```

------------------------------------------------------------------------


##  Project Structure

```
PHP_Laravel12_ClientSide_Form_Validation/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── FormController.php
│   └── Models/
│       └── User.php   // default Use
│
├── database/
│   └── migrations/
│       └── create_users_table.php  //default Use
│
├── resources/
│   └── views/
│       └── createUser.blade.php
│
├── routes/
│   └── web.php
│
├── .env
├── README.md
├── composer.json
├── package.json
└── artisan
```


------------------------------------------------------------------------

## Output

ClientSide Form Validation

<img width="1918" height="1029" alt="Screenshot 2026-01-05 114452" src="https://github.com/user-attachments/assets/052394f2-acff-4c15-8261-47dbc3e4947f" />

<img width="1919" height="1026" alt="Screenshot 2026-01-05 114846" src="https://github.com/user-attachments/assets/af0c5b46-74b5-40ef-8be2-04fc7a344c0f" />

After Form Submit

<img width="1919" height="1026" alt="Screenshot 2026-01-05 114906" src="https://github.com/user-attachments/assets/91d4fce5-bc15-444c-9dfb-8e65a259c280" />


------------------------------------------------------------------------

Your PHP_Laravel12_ClientSide_Form_Validation Project is Now Ready!
<<<<<<< HEAD
=======
>>>>>>> main

## Introduction

Client-side form validation improves user experience by validating user
input instantly in the browser before sending data to the server.

This project demonstrates **Client-Side Form Validation in Laravel 12
using jQuery Validation Plugin**, along with **server-side validation**
for security.

This project is beginner-friendly and suitable for freshers, academic
submissions, and interviews.

------------------------------------------------------------------------

##  Project Overview

**Project Name:** PHP_Laravel12_ClientSide_Form_Validation\
**Framework:** Laravel 12\
**Validation Types:** - Client-Side Validation (jQuery) - Server-Side
Validation (Laravel)

###  Objective

-   Validate form inputs in browser
-   Prevent invalid submissions
-   Display instant error messages
-   Save data securely
-   Show success message after submission

------------------------------------------------------------------------

##  Tech Stack

-   Laravel 12
-   PHP 8+
-   jQuery
-   jQuery Validate Plugin
-   Bootstrap 5
-   MySQL

------------------------------------------------------------------------

##  Step 1: Create Laravel 12 Project

``` bash
composer create-project laravel/laravel PHP_Laravel12_ClientSide_Form_Validation "12.*"
cd PHP_Laravel12_ClientSide_Form_Validation
```

------------------------------------------------------------------------

##  Step 2: Environment Configuration (.env)

``` env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=client_form_validation
DB_USERNAME=root
DB_PASSWORD=
```

After create database using this command:

``` bash
php artisan migrate
```

------------------------------------------------------------------------

##  Step 3: User Migration (Default Use)

###  Migration File Location:

database/migrations/xxxx_xx_xx_create_users_table.php

``` php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->rememberToken();
    $table->timestamps();
});
```

Run:

``` bash
php artisan migrate
```

------------------------------------------------------------------------

##  Step 4: User Model (Default Use)

###  File Location

app/Models/User.php

``` php
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
```

------------------------------------------------------------------------

##  Step 5: Controller

``` bash
php artisan make:controller FormController
```

File: App\Http\Controllers\FormController

``` php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

/**
 * FormController
 * 
 * This controller handles:
 * - Showing the user creation form
 * - Storing user data after validation
 */
class FormController extends Controller
{
    /**
     * Show the user creation form
     * 
     * This method simply returns the Blade view
     * where the form is displayed.
     */
    public function create()
    {
        return view('createUser');
    }

    /**
     * Store the submitted form data
     * 
     * This method:
     * 1. Validates the request data
     * 2. Encrypts the password
     * 3. Saves user data into the database
     * 4. Redirects back with a success message
     */
    public function store(Request $request)
    {
        // Server-side validation for security
        $validatedData = $request->validate([
            'name'     => 'required',              // Name field is required
            'email'    => 'required|email|unique:users', // Email must be valid and unique
            'password' => 'required|min:5',         // Password must be at least 5 characters
        ]);

        // Encrypt the password before saving
        $validatedData['password'] = bcrypt($validatedData['password']);

        // Store user data into the users table
        User::create($validatedData);

        // Redirect back to form with success message
        return back()->with('success', 'User successfully created!');
    }
}
```

------------------------------------------------------------------------

##  Step 6: Routes

File: routes/web.php

``` php
use App\Http\Controllers\FormController;

Route::get('/users/create', [FormController::class, 'create']);
Route::post('/users/create', [FormController::class, 'store'])->name('users.store');
```

------------------------------------------------------------------------

##  Step 7: Blade View & Client-Side Validation

File: resources/views/createUser.blade.php

-   Uses jQuery Validate Plugin
-   Instant validation feedback
-   Prevents invalid form submission

Validation Rules: - Name: Required - Email: Required & valid - Password:
Minimum 5 characters - Confirm Password: Must match


```html
<!DOCTYPE html>
<html>
<head>
    <!-- Page Title -->
    <title>Laravel 12 Client-Side Form Validation</title>

    <!-- Bootstrap CSS for basic styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- jQuery Validation Plugin for client-side form validation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <!-- Custom style for validation error messages -->
    <style>
        label.error {
            color: #dc3545;      /* Red color for error text */
            font-size: 14px;     /* Slightly smaller font */
        }
    </style>
</head>
<body>

<!-- Main container -->
<div class="container mt-5">

    <!-- Success message displayed after user creation -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- User Registration Form -->
    <form id="regForm" method="POST" action="{{ route('users.store') }}">
        @csrf <!-- CSRF protection token -->

        <!-- Name Field -->
        <div class="mb-3">
            <label for="inputName" class="form-label">Name:</label>
            <input type="text" name="name" id="inputName" class="form-control">
        </div>

        <!-- Email Field -->
        <div class="mb-3">
            <label for="inputEmail" class="form-label">Email:</label>
            <input type="text" name="email" id="inputEmail" class="form-control">
        </div>

        <!-- Password Field -->
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>

        <!-- Confirm Password Field -->
        <div class="mb-3">
            <label for="inputPassword" class="form-label">Confirm Password:</label>
            <input type="password" name="confirm_password" id="inputPassword" class="form-control">
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<!-- jQuery Client-Side Validation Script -->
<script>
$(document).ready(function(){

    // Apply validation on form submit
    $("#regForm").validate({

        // Validation rules
        rules: {
            name: { 
                required: true            // Name must be filled
            },
            email: { 
                required: true,           // Email is required
                email: true               // Must be valid email format
            },
            password: { 
                required: true,           // Password is required
                minlength: 5              // Minimum 5 characters
            },
            confirm_password: {
                required: true,           // Confirm password required
                equalTo: "#password"      // Must match password field
            }
        },

        // Custom error messages
        messages: {
            name: "Name is required",
            email: {
                required: "Email is required",
                email: "Enter a valid email"
            },
            password: {
                required: "Password is required",
                minlength: "Minimum 5 characters"
            },
            confirm_password: {
                required: "Confirm password is required",
                equalTo: "Passwords do not match"
            }
        }
    });
});
</script>

</body>
</html>
```
------------------------------------------------------------------------


##  Step 8: Run Project

``` bash
php artisan serve
```

Visit:

```
http://127.0.0.1:8000/users/create
```

------------------------------------------------------------------------


##  Project Structure

```
PHP_Laravel12_ClientSide_Form_Validation/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── FormController.php
│   └── Models/
│       └── User.php   // default Use
│
├── database/
│   └── migrations/
│       └── create_users_table.php  //default Use
│
├── resources/
│   └── views/
│       └── createUser.blade.php
│
├── routes/
│   └── web.php
│
├── .env
├── README.md
├── composer.json
├── package.json
└── artisan
```


------------------------------------------------------------------------

## Output

ClientSide Form Validation

<img width="1918" height="1029" alt="Screenshot 2026-01-05 114452" src="https://github.com/user-attachments/assets/052394f2-acff-4c15-8261-47dbc3e4947f" />

<img width="1919" height="1026" alt="Screenshot 2026-01-05 114846" src="https://github.com/user-attachments/assets/af0c5b46-74b5-40ef-8be2-04fc7a344c0f" />

After Form Submit

<img width="1919" height="1026" alt="Screenshot 2026-01-05 114906" src="https://github.com/user-attachments/assets/91d4fce5-bc15-444c-9dfb-8e65a259c280" />


------------------------------------------------------------------------

Your PHP_Laravel12_ClientSide_Form_Validation Project is Now Ready!
=======
>>>>>>> origin/development
