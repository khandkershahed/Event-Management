{{-- <!DOCTYPE html>
<html>

<head>
    <title>Email Verification</title>
</head>

<body>
    <p>Dear {{ $user->name }},</p>
    <p>Your email verification code is: <strong>{{ $code }}</strong></p>
    <p>Enter this code to verify your email.</p>
</body>

</html> --}}

<!DOCTYPE html>
<html>

<head>
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e4dbdbb9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            background-color: #ada4a4a2;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }



        .content {
            padding: 20px;
        }

        .verification-code {
            font-size: 24px;
            font-weight: bold;
            background-color: #e0e4e9;
            padding: 10px;
            display: inline-block;
            border-radius: 5px;
            margin: 20px 0;
        }

        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #666666;
        }

        .btn {
            background-color: #007BFF;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 18px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">


        <div class="content">
            <p>Dear <strong>{{ $user->name }}</strong>,</p>
            <p>Your email verification code is:</p>
            <p class="verification-code">{{ $code }}</p>
            <p>Please enter this code in the verification form to activate your account.</p>
            <a href="{{ url('/email/verify') }}" class="btn">Verify Now</a>
        </div>

        <div class="footer">
            <p>If you did not request this, please ignore this email.</p>
            <p>Thank you, <br> <strong>Your New Site</strong></p>
        </div>
    </div>
</body>

</html>
