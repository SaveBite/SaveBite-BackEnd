<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Code Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333333;
            line-height: 1.6;
        }
        .email-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background-color: #1db91f;
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 20px;
            text-align: center;
        }
        .email-body h3 {
            margin: 20px 0;
            font-size: 18px;
        }
        .email-body .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #128506;
            margin: 20px 0;
        }
        .email-body .note {
            font-size: 14px;
            color: #ff0000;
            margin-top: 20px;
        }
        .email-footer {
            background-color: #1db91f;
            text-align: center;
            padding: 15px;
            font-size: 14px;
            color: #888888;
        }
        .email-footer a {
            color: #128506;
            text-decoration: none;
        }
        .email-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="email-header">
        <h1>Welcome to SaveBite </h1>
    </div>
    <div class="email-body">
        <h3>Your code for email verification is:</h3>
        <div class="otp-code">{{ $code }}</div>
    </div>
    <div class="email-footer">
        <p>Need help? <a href="{{ config('app.url') }}">Visit our support page</a></p>
        <p>&copy; {{ date('Y') }} SaveBite. All rights reserved.</p>
    </div>
</div>
</body>
</html>
