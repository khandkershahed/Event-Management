{{-- <!DOCTYPE html>
<html>

<head>
    <title>Verify Your Email</title>
</head>

<body>
    <h2>Enter Verification Code</h2>
    <form method="POST" action="{{ route('verification.verify') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        <label>Verification Code:</label>
        <input type="text" name="code" required>

        <button type="submit">Verify</button>
    </form>
</body>

</html> --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        body {
            background: #97989967;
            font-family: 'Arial', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            max-width: 900px;
        }

        .card {
            border-radius: 5px;
            overflow: hidden;
        }

        .card-body {
            padding: 40px;
        }

        .form-control {
            border-radius: 5px;
            padding: 10px;
        }

        .btn-common-one {
            background: #013c7a;
            color: #fff;
            font-weight: bold;
            padding: 10px;
            border-radius: 5px;
            transition: 0.3s;
        }

        .btn-common-one:hover {
            background: #0563c2;
            color: #fff;
        }

        .input-group-text {
            background: #f1f1f1;
            border-radius: 10px 0 0 10px;
            border: none;
        }

        .form-outline {
            position: relative;
        }

        .form-outline i {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #aaa;
        }

        .form-control {
            padding-left: 20px;
        }

        .login-link {
            text-decoration: none;
            font-weight: bold;
            color: #007bff;
        }

        .login-link:hover {
            color: #0a0a0a;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <p class="pb-1 text-center">Welcome Here!</p>
                        <h2 class="mb-4 fw-bold text-center">Enter Verification Code</h2>

                        <!-- Display Success Message -->
                        @if (session('success'))
                            <div class="alert alert-success mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Display Validation Errors -->
                        @if ($errors->any())
                            <div class="alert alert-danger mb-0">
                                <p class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <span class="mb-0">{{ $error }}</span>
                                    @endforeach
                                </p>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('verification.verify') }}">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email }}">

                            <!-- Code -->
                            <div class="mb-3 form-outline">
                                <i class="fas fa-envelope"></i>
                                <input type="text" class="form-control" name="code" placeholder="Email Code"
                                    required>
                            </div>


                            <!-- Login Button -->
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-common-one">Verify Now</button>
                            </div>

                            <!-- No Registered? -->
                            <div class="text-center mt-2">
                                <p><a href="" class="login-link">Back to site?</a></p>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
